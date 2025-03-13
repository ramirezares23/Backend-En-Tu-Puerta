<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Service;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('petitions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class,'id_user')->constrained()->onDelete('cascade');
            
            $table->date('date');
            $table->string('status',50);

            $table->time('time'); //HH:MM:SS. Hora a la que se solicita el servicio
            $table->text('message');

            $table->foreignIdFor(Service::class,'id_service')->constrained()->onDelete('cascade');

            $table->timestamps(); // created_at and updated_at

            //TODO: Visto bueno con equipo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petitions');
    }
};
