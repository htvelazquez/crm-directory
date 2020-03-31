<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Requests\StoreContact;
use App\Services\ContactsService;
use App\Contact;
use App\Snapshot;
use App\Company;
use App\SnapshotExperience;
use App\Location;
use App\SnapshotMetadata;
use App\SnapshotPhone;
use App\SnapshotEmail;
use App\School;
use App\StudyField;
use App\SnapshotEducation;
use App\Skill;
use App\SnapshotSkill;
use App\Language;
use App\SnapshotLanguage;
use App\Label;
use App\LabelContact;

class ContactsController extends Controller
{
    public function get(Request $request)
    {
        $page = !empty($request->page) ? (int) $request->page : 1;
        $limit = !empty($request->limit) ? (int) $request->limit : 20;
        $offset = $limit * ($page - 1);
        $company = $request->company;
        $relevance = $request->relevance;

        $accountId = 1; // sacar de token

        /*********************** TAGS FILTER **********************/
        $whereContactLabels = '';
        if (!empty($request->tags)){
            $tags = explode(',',$request->tags);

            $labelIds = [];
            $labelsRows = DB::table('labels')->whereIn('name',$tags)->where('account_id',$accountId)->orderBy('id')->get();
            if (!empty($labelsRows)){
                foreach ($labelsRows as $labelRow) {
                    $labelIds[] = $labelRow->id;
                }
            }
            $labelIds = (!empty($labelIds))? implode(',',$labelIds) : '0';

            $contactsLabeled = DB::select("SELECT contact_id, GROUP_CONCAT(DISTINCT label_id ORDER BY label_id SEPARATOR ',') labels FROM label_contacts WHERE label_id IN ($labelIds) GROUP BY contact_id HAVING labels = '$labelIds'");
            $contactsLabeledIds = [];
            if (!empty($contactsLabeled)){
                foreach ($contactsLabeled as $contactLabeled) {
                    $contactsLabeledIds[] = $contactLabeled->contact_id;
                }
            }
            $contactsLabeledIds = (!empty($contactsLabeledIds))? implode(',',$contactsLabeledIds) : '0';

            $whereContactLabels = " WHERE contact_id IN ($contactsLabeledIds)";
        }

        /********************** TITLE FILTER **********************/
        $title = $request->title;
        $whereExperience = (!empty($title))? " sex.jobTitle LIKE '%$title%'" : 'sex.main_position IS TRUE';

        /********************* LANGUAGE FILTER ********************/
        $language = $request->language;
        $subQueryLang = '';
        if (!empty($language)){
            $snpahotsLang = DB::select("SELECT snapshot_id FROM snapshot_languages WHERE language_id IN (SELECT id FROM languages WHERE iso2code = '{$language}') AND snapshot_id IN (SELECT MAX(id) id FROM snapshots GROUP BY contact_id)");
            $snapIds = [];
            if (!empty($snpahotsLang)){
                foreach ($snpahotsLang as $snapLang) {
                    $snapIds[] = $snapLang->snapshot_id;
                }
            }
            $snapIds = (!empty($snapIds))? implode(',',$snapIds) : '0';
            $subQueryLang = " AND s.id IN ($snapIds)";
        }

        /********************* LOCATION FILTER ********************/
        $location = $request->location;
        $whereLocation = (!empty($location))? "AND loc.label LIKE '%$location%'" : '';

        // $whereStart = '';
        // if (!empty($start)) {
        //     $whereStart = "AND sex.created_at >= '$start'";
        // }
        //
        // $whereEnd = '';
        // if (!empty($end)) {
        //     $whereEnd = "AND sex.created_at <= '$end'";
        // }

        $selectData = "SELECT s.id snapshot_id, s.contact_id, met.firstName, met.lastName, met.publicURL, sex.jobTitle, com.label company, sex.from, loc.label location, com.linkedin_id `companyLId`, com.link, sex.created_at `createdAt` ";
        $selectCount = "SELECT COUNT(*) `tot` ";

        $sql = "FROM account_contacts ac
            INNER JOIN (
            	SELECT MAX(id) id, contact_id
                FROM snapshots
                $whereContactLabels
                GROUP BY contact_id
            ) s ON (s.contact_id = ac.contact_id)
            INNER JOIN snapshot_experiences sex ON (sex.snapshot_id = s.id AND $whereExperience)
            INNER JOIN companies com ON (com.id = sex.company_id)
            INNER JOIN snapshot_metadatas met ON (met.snapshot_id = s.id)
            INNER JOIN locations loc ON (met.location_id = loc.id $whereLocation)
            WHERE ac.account_id = $accountId $subQueryLang";
        // $whereStart
        // $whereEnd";

        $dataTotal = DB::select($selectCount.$sql);
        $total = (!empty($dataTotal[0]->tot))? $dataTotal[0]->tot : 0;

        $sql .= " LIMIT $offset, $limit;";

        $data = ($total > 0)? DB::select($selectData.$sql) : null;

        if (!empty($data)){
            foreach ($data as $contact) {
                $contact->labels = ContactsService::getInstance()->getLabelsByAccount($contact->contact_id, 1);
                $contact->languages = ContactsService::getInstance()->getLanguagesBySnapshot($contact->snapshot_id);
            }
        }

        $return['total'] = $total;
        $return['contacts'] = $data;
        $return['page'] = $page;
        $return['limit'] = $limit;

        return response()->json($return);
    }

    public function download(Request $request) {
        $company = $request->company;
        $relevance = $request->relevance;
        $start = $request->start;
        $end = $request->end;

        $whereStart = '';
        if (!empty($start)) {
            $whereStart = "AND sex.created_at >= '$start'";
        }

        $whereEnd = '';
        if (!empty($end)) {
            $whereEnd = "AND sex.created_at <= '$end'";
        }

        $sql = "
        SELECT
        c.linkedin_id `contactLId`,
        met.name,
        met.firstName,
        met.lastName,
        sex.jobTitle,
        sex.relevance,
        sex.from,
        com.label,
        com.linkedin_id `companyLId`,
        com.url,
        sex.created_at `createdAt`
        FROM snapshot_experiences sex
        INNER JOIN (
            SELECT
            MAX(id) id,
            contact_id
            FROM snapshots
            GROUP BY contact_id
        ) s
        ON (s.id = sex.snapshot_id)
        INNER JOIN contacts c
        ON (c.id = s.contact_id)
        INNER JOIN companies com
        ON (com.id = sex.company_id)
        INNER JOIN snapshot_metadatas met
        ON (met.snapshot_id = s.id)
        WHERE `to` IS NULL
        $whereStart
        $whereEnd";

        $headers = array(
            'Content-Type'        => 'text/csv',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Disposition' => 'attachment; filename=leadgen.csv',
            'Expires'             => '0',
            'Pragma'              => 'public',
        );

        $data = DB::select($sql);

        $response = new StreamedResponse(function() use($data) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'name','firstName','lastName','contactLId','relevance','jobTitle','from','company','companyLId','url','date'
            ]);

            foreach ($data as $line) {
                $dataCsv = [
                    $line->name,
                    $line->firstName,
                    $line->lastName,
                    $line->contactLId,
                    $line->relevance,
                    $line->jobTitle,
                    $line->from,
                    $line->label,
                    $line->companyLId,
                    $line->url,
                    $line->createdAt
                ];

                fputcsv($handle, $dataCsv);
            }

            fclose($handle);
        }, 200, $headers);

        return $response->send();
    }

    protected function normalizeString(string $string) {
        $string = strtolower($string);

        $string = preg_replace("/[^a-z0-9_\s-+]/", "", $string);

        $string = preg_replace("/[\s-]+/", " ", $string);

        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

}
