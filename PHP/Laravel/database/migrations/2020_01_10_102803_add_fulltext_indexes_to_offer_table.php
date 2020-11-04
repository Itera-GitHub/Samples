<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFulltextIndexesToOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE cv_offre ADD FULLTEXT IDX_OFFRE_FULLTEXT (intitule_offre,description_offre,profil_candidat_offre,mission_offre)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE cv_candidat DROP INDEX IDX_OFFRE_FULLTEXT');
    }
}
