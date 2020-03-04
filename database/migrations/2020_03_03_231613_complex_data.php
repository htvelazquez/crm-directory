<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ComplexData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('snapshot_metadatas', function ($table) {
            $table->string('publicURL',128)->nullable()->after('lastName');
            $table->boolean('premium')->nullable()->after('publicURL');
        });

        Schema::create('languages_metadatas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',64);
            $table->string('family',64)->nullable();
            $table->string('iso2code',2)->unique();
            $table->string('icon', 128)->nullable();
        });

        Schema::table('languages', function (Blueprint $table) {
            $table->string('iso2code')->nullable()->after('label');

            $table->foreign('iso2code')->references('iso2code')->on('languages_metadatas');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->dropForeign('languages_iso2code_foreign');
            $table->dropColumn('iso2code');
        });

        Schema::dropIfExists('languages_metadatas');

        Schema::table('snapshot_metadatas', function ($table) {
            $table->dropColumn('publicURL');
        });
    }
}
