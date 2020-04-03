<?php

use Illuminate\Database\Seeder;

class AccountsUsersMetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert([
            [
                'id'    => 1,
                'name'  => 'Cirenio Interno'
            ],
            [
                'id'    => 2,
                'name'  => 'Cirenio Developers'
            ]
        ]);

        DB::table('users')->insert([
            [
                'account_id'    => 1,
                'name'          => 'Fernando Monzon',
                'email'         => 'fernando@cirenio.com'
            ],
            [
                'account_id'    => 2,
                'name'          => 'Hernan Velazquez',
                'email'         => 'hernan@cirenio.com'
            ]
        ]);


        DB::table('labels')->insert([
            [
                'name'          => 'Empleado',
                'color'         => 'FCA903',
                'account_id'    => 1
            ],
            [
                'name'          => 'Freelancer',
                'color'         => '01630B',
                'account_id'    => 1
            ],
            [
                'name'          => 'Argentina',
                'color'         => '03BAFC',
                'account_id'    => 1
            ],
            [
                'name'          => 'Internacional',
                'color'         => 'A83434',
                'account_id'    => 1
            ]
        ]);

        DB::table('account_contacts')->insert([
            [
                'account_id'    => 1,
                'contact_id'    => 1,
                'updated_by'    => 1,
                'created_by'    => 1
            ],
            [
                'account_id'    => 1,
                'contact_id'    => 2,
                'updated_by'    => 1,
                'created_by'    => 1
            ],
            [
                'account_id'    => 2,
                'contact_id'    => 1,
                'updated_by'    => 2,
                'created_by'    => 2
            ],
            [
                'account_id'    => 2,
                'contact_id'    => 2,
                'updated_by'    => 2,
                'created_by'    => 2
            ]
        ]);

        DB::table('label_contacts')->insert([
            [
                'contact_id'    => 1,
                'label_id'      => 1
            ],
            [
                'contact_id'    => 1,
                'label_id'      => 3
            ],
            [
                'contact_id'    => 2,
                'label_id'      => 2
            ],
            [
                'contact_id'    => 2,
                'label_id'      => 4
            ]
        ]);
    }
}
