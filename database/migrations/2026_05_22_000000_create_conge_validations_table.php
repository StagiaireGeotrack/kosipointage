<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conge_validations', function (Blueprint $table) {
            $table->id();

            // Informations de l'employé (saisie libre — pas obligatoirement lié à un enregistrement)
            $table->string('matricule')->nullable();
            $table->string('nom_prenom');
            $table->string('email');
            $table->string('telephone')->nullable();

            // Période de congé demandée
            $table->dateTime('date_heure_debut');
            $table->dateTime('date_heure_fin');

            // Statut : en_cours | validated | not_validated
            $table->enum('status', ['en_cours', 'validated', 'not_validated'])
                  ->default('en_cours');

            // Horodatages métier (séparés des timestamps Laravel)
            $table->timestamp('date_creation')->useCurrent();
            $table->timestamp('date_validation')->nullable();

            $table->timestamps(); // created_at / updated_at Laravel standard
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conge_validations');
    }
};
