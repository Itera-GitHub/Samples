<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFulltextIndexesToCandidatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE cv_candidat ADD FULLTEXT IDX_CANDIDAT_FULLTEXT (nom_candidat,prenom_candidat,adresse_candidat,code_postal_candidat, loisir_candidat,ville_candidat, titre_candidat, region_candidat,resume_competence,resume_formation,resume_experience,resume_langue,titre_mini,desc_mini,poste_recherche_candidat)');
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE cv_candidat DROP INDEX IDX_CANDIDAT_FULLTEXT');
    }
}
