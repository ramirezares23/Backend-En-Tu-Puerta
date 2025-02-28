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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            
            $table->foreignIdFor(User::class,'provider_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(User::class,'client_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(Service::class,'service_id')->constrained()->onDelete('cascade');
            
            $table->date('date');
            $table->string('status',50);

            $table->time('time');//HH:MM:SS.

            $table->timestamps();

            //TODO: Visto bueno con equipo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
