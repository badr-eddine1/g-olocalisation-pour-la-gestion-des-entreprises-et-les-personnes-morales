<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
       $table->id();
            $table->string('nom_entreprise', 255);
            $table->string('code_ice', 50)->nullable();
            $table->string('rc', 50)->nullable()->unique();


            $table->enum('forme_juridique', ['SA', 'SARL', 'SNC', 'SCS', 'autre'])->nullable();
            $table->enum('type', ['PP', 'PM'])->nullable();
            $table->enum('taille_entreprise', ['PME', 'GE', 'SU'])->nullable();
            $table->enum('en_activite', ['oui', 'non'])->default('oui')->nullable();


            $table->text('adresse');
            $table->string('ville', 255)->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);


            $table->text('secteur')->nullable();
            $table->string('activite', 255)->nullable();
            $table->text('certifications')->nullable();


            $table->string('email', 255)->nullable();
            $table->string('tel', 20)->nullable();
            $table->string('fax', 50)->nullable();
            $table->string('contact', 255)->nullable();
            $table->string('site_web', 255)->nullable();


            $table->string('cnss', 50)->nullable();
            $table->string('if', 50)->nullable();
            $table->string('patente', 50)->nullable();


            $table->timestamp('date_creation')->useCurrent();
            $table->timestamps();

            $table->index('code_ice');
            $table->index('ville');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
