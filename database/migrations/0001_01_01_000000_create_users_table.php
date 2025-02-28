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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('code',8);
            $table->string('first_name',50);
            $table->string('last_name',50);

            $table->string('username',30);

            $table->string('email',100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_number',15)->unique();
            $table->string('identity_document',255)->unique();
            $table->string('password');

            $table->string('address', 255)->nullable(); //Lo puede completar una vez registrado
            $table->boolean('terms_and_conditions_accept');
            
            $table->time('start_time')->nullable(); //El cliente no tiene rubro. EL prestador puede completarlo luego de iniciar sesion
            $table->time('end_time')->nullable(); //El cliente no tiene rubro. EL prestador puede completarlo luego de iniciar sesion
            $table->string('type',20)->nullable(); // EL cliente no tiene rubro
            
            $table->json('profile_image_path')->nullable(); //Lo puede completar una vez registrado
            $table->integer('punctuation')->default(0);

            $table->boolean('is_verified')->nullable();
            
            $table->timestamps(); // created_at and updated_at
            $table->rememberToken();

            //TODO: Visto bueno con equipo
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
