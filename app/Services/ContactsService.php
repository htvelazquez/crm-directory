<?php

namespace App\Services;

use \Illuminate\Support\Facades\DB;

class ContactsService extends Service
{
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * @return ContactsService
     */
    public static function getInstance()
    {
        return parent::build(self::class);
    }

    public function getLabelsByAccount($contactId, $accountId)
    {
        return DB::table('labels')
            ->join('label_contacts', 'labels.id', '=', 'label_contacts.label_id')
            ->where([
                ['labels.account_id',$accountId],
                ['label_contacts.contact_id', $contactId]
            ])->get(['label_id', 'name', 'color'])->toArray();
    }

    public function getLanguagesBySnapshot($snapshotId)
    {
        return DB::table('snapshot_languages')
            ->join('languages', 'snapshot_languages.language_id', '=', 'languages.id')
            ->leftJoin('languages_metadatas', 'languages_metadatas.iso2code', '=', 'languages.iso2code')
            ->where('snapshot_languages.snapshot_id',$snapshotId)
            ->get(['languages.label', 'languages.iso2code', 'languages_metadatas.icon', 'snapshot_languages.proficiency'])->toArray();
    }

}
