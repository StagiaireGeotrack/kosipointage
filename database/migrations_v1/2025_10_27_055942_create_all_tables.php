<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Création de la table Entreprises_sieges en premier (car référencée par d'autres tables)
        Schema::create('Entreprises_sieges', function (Blueprint $table) {
            $table->unsignedInteger('ID')->autoIncrement();
            $table->string('Nom', 255)->nullable(false);
            $table->text('Nom_Lieu_Ville')->nullable();
            $table->boolean('Actived')->default(0);
        });

        // Table Administration
        Schema::create('administration', function (Blueprint $table) {
            $table->unsignedInteger('ID')->autoIncrement();
            $table->string('Identifiant_email', 255)->nullable(false);
            $table->text('Password_')->nullable();
            $table->boolean('IsSuperAdmin')->default(0);
            $table->unsignedInteger('SiegeID')->nullable();
            
            $table->foreign('SiegeID')
                ->references('ID')
                ->on('Entreprises_sieges')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->timestamps(); // Ajout pour Laravel
            $table->rememberToken(); // Ajout pour auth Laravel
        });

        // Table Entreprises
        Schema::create('Entreprises', function (Blueprint $table) {
            $table->unsignedInteger('ID')->autoIncrement();
            $table->string('Nom', 255)->nullable(false);
            $table->text('Logo')->nullable();
            $table->text('Nom_Lieu_Ville')->nullable();
            $table->decimal('Latitude', 18, 8)->nullable(false);
            $table->decimal('Longitude', 18, 8)->nullable(false);
            $table->decimal('RadiusInMeters', 18, 8)->default(10);
            $table->datetime('CreatedAt')->useCurrent();
            $table->boolean('Actived')->default(0);
            $table->unsignedInteger('SiegeID')->nullable(false);
            
            $table->foreign('SiegeID')
                ->references('ID')
                ->on('Entreprises_sieges')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // Table Employes
        Schema::create('Employes', function (Blueprint $table) {
            $table->unsignedInteger('ID')->autoIncrement();
            $table->string('Nom', 255)->nullable(false);
            $table->string('BadgeID', 25)->nullable(false);
            $table->boolean('HasBiometricSetup')->default(0);
            $table->boolean('HasFaceSetup')->default(0);
            $table->text('FaceEncodingPath')->nullable();
            $table->text('Pin')->nullable();
            $table->datetime('CreatedAt')->useCurrent();
            $table->boolean('Actived')->default(0);
            $table->unsignedInteger('SiegeID')->nullable(false);
            
            $table->foreign('SiegeID')
                ->references('ID')
                ->on('Entreprises_sieges')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // Table Pointages
        Schema::create('Pointages', function (Blueprint $table) {
            $table->unsignedInteger('ID')->autoIncrement();
            $table->unsignedInteger('employee_id')->nullable(false);
            $table->string('type_', 25)->nullable(false);
            $table->string('auth_method', 25)->nullable(false);
            $table->datetime('timestamp_')->useCurrent();
            $table->decimal('latitude', 18, 8)->nullable(false);
            $table->decimal('longitude', 18, 8)->nullable(false);
            $table->text('photo_path')->nullable();
            $table->boolean('synced')->default(0);
            $table->unsignedInteger('SiegeID')->nullable(false);
            
            $table->foreign('employee_id')
                ->references('ID')
                ->on('Employes')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->foreign('SiegeID')
                ->references('ID')
                ->on('Entreprises_sieges')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Pointages');
        Schema::dropIfExists('Employes');
        Schema::dropIfExists('Entreprises');
        Schema::dropIfExists('administration');
        Schema::dropIfExists('Entreprises_sieges');
    }
};
