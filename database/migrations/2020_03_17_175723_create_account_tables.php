<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',64);
            $table->timestamps();
        });

        Schema::table('users', function ($table) {
            $table->integer('account_id')->default(NULL)->nullable()->unsigned()->after('id');
            $table->dropColumn('password');
            $table->dropColumn('remember_token');

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        Schema::create('account_contacts', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('account_id')->unsigned();
            $table->integer('contact_id')->unsigned();
            $table->text('comments')->nullable();
            $table->integer('updated_by')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');

            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('labels', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('account_id')->unsigned();
            $table->string('name',64);
            $table->string('color',6)->nullable();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        Schema::create('label_contacts', function(Blueprint $table)
        {
            $table->primary(['contact_id', 'label_id']);
            $table->unsignedInteger('contact_id');
            $table->unsignedInteger('label_id');

            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_contacts');
        Schema::dropIfExists('label_contacts');
        Schema::dropIfExists('labels');

        Schema::table('users', function ($table) {
            $table->dropForeign('users_account_id_foreign');
            $table->dropColumn('account_id');
            $table->string('password')->after('email');
            $table->rememberToken()->after('password');
        });

        Schema::dropIfExists('accounts');


    }
}
