<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_search_services_by_exact_name()
    {
        // Crear un usuario y obtener sus credenciales
        $user = User::factory()->create([
            'email' => 'chanelle60@example.com',
            'password' => bcrypt('password') 
        ]);

        // Autenticación con el primer usuario
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);


        $token = $response->json('data.token');

        // Crear servicio
        Service::factory()->create(['service_name' => 'Limpieza General']);

        // Realizar solicitud con token
        $response = $this->getJson('/api/v1/services?filter[name]=Limpieza General', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['service_name' => 'Limpieza General']);
    }

    /** @test */
    public function it_can_search_services_by_partial_name()
    {
        // Crear un usuario y obtener sus credenciales
        $user = User::factory()->create([
            'email' => 'chanelle60@example.com',
            'password' => bcrypt('password')
        ]);

        // Autenticación con el primer usuario
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $token = $response->json('data.token');

        // Crear servicios
        Service::factory()->create(['service_name' => 'Limpieza de casas']);
        Service::factory()->create(['service_name' => 'Limpieza profunda']);
        Service::factory()->create(['service_name' => 'Reparación eléctrica']);

        // Realizar solicitud con token
        $response = $this->getJson('/api/v1/services?filter[name]=*Limpieza*', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['service_name' => 'Limpieza de casas'])
            ->assertJsonFragment(['service_name' => 'Limpieza profunda']);
    }

    /** @test */
    public function it_returns_empty_when_no_services_found()
    {
        // Crear un usuario y obtener sus credenciales
        $user = User::factory()->create([
            'email' => 'chanelle60@example.com',
            'password' => bcrypt('password')
        ]);

        // Autenticación con el primer usuario
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $token = $response->json('data.token');

        // Crear servicio
        Service::factory()->create(['service_name' => 'Reparación eléctrica']);

        // Realizar solicitud con token
        $response = $this->getJson('/api/v1/services?filter[name]=Fontanería', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function it_can_search_services_case_insensitive()
    {
        // Crear un usuario y obtener sus credenciales
        $user = User::factory()->create([
            'email' => 'chanelle60@example.com',
            'password' => bcrypt('password')
        ]);

        // Autenticación con el primer usuario
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $token = $response->json('data.token');

        // Crear servicios
        Service::factory()->create(['service_name' => 'Limpieza General']);
        Service::factory()->create(['service_name' => 'limpieza general']);

        // Realizar solicitud con token
        $response = $this->getJson('/api/v1/services?filter[name]=LIMPIEZA GENERAL', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }
}
