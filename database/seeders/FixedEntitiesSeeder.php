<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;

class FixedEntitiesSeeder extends Seeder
{
    public function run()
    {
        // Crear un cliente
        $cliente = User::create([
            'code' => '00000001',
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'username' => 'juanperez',
            'email' => 'testcliente@example.com',
            'address' => fake()->address(),
            'phone_number' => '1234567890',
            'identity_document' => '12345678',
            'password' => Hash::make('password'),
            'terms_and_conditions_accept' => true,
            'start_time' => '00:00:00',
            'end_time' => '23:59:59',
            'type' => 'Cliente',
            'profile_image_path' => json_encode('https://i.pinimg.com/280x280_RS/61/1e/29/611e298177035a4ff0191a72b95d0976.jpg'),
            'email_verified_at' => now(),
            'is_verified' => true,
        ]);

        // Crear dos prestadores
        $prestador1 = User::create([
            'code' => '00000002',
            'first_name' => 'Carlos',
            'last_name' => 'Gómez',
            'username' => 'carlosgomez',
            'email' => 'testprestador1@example.com',
            'phone_number' => '0987654321',
            'identity_document' => '87654321',
            'password' => Hash::make('password'),
            'address' => fake()->address(),
            'terms_and_conditions_accept' => true,
            'start_time' => '08:00:00',
            'end_time' => '17:00:00',
            'type' => 'Jardinería',
            'profile_image_path' => json_encode('https://st4.depositphotos.com/1017986/39200/i/450/depositphotos_392006782-stock-photo-happy-man-in-apron-at.jpg'),
            'email_verified_at' => now(),
            'is_verified' => true,
        ]);

        $prestador2 = User::create([
            'code' => '00000003',
            'first_name' => 'Ana',
            'last_name' => 'Martínez',
            'username' => 'anamartinez',
            'email' => 'testprestador2@example.com',
            'phone_number' => '1122334455',
            'identity_document' => '11223344',
            'password' => Hash::make('password'),
            'address' => fake()->address(),
            'terms_and_conditions_accept' => true,
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'type' => 'Peluquería',
            'profile_image_path'=> json_encode('https://previews.123rf.com/images/john79/john791708/john79170800031/84347480-chicas-de-perfil-para-sal%C3%B3n-de-belleza-y-peluquer%C3%ADa-con-tijeras-y-peine.jpg'),
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Crear servicios asociados a los prestadores
        Service::create([
            'id_provider' => $prestador1->id,
            'service_name' => 'Servicio de Jardinería',
            'service_price' => 50.00,
            'description' => 'Mantenimiento general de jardines en patios, urbanizaciones, casas, edificios, Y MÁS.',
            'images_path' => json_encode(['https://imagensubir.infojardin.com/subido/images/viu1272509248r.JPG', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRRjRyCVgaEzcJmR0KllE0tyK5OzK7LgwcG0Q&s']),
            'duration' => 120,
        ]);

        Service::create([
            'id_provider' => $prestador1->id,
            'service_name' => 'Poda de Árboles',
            'service_price' => 80.00,
            'description' => 'Poda profesional de árboles altos.',
            'images_path' => json_encode(['https://www.infocampo.com.ar/wp-content/uploads/2023/05/poda1o.jpg', 'https://www.clarin.com/2024/04/22/zgueqGxTN_2000x1500__1.jpg']),
            'duration' => 180,
        ]);



        Service::create([
            'id_provider' => $prestador2->id,
            'service_name' => 'Corte de Cabello (Dama)',
            'service_price' => 20.00,
            'description' => 'No incluye lavado. Aplica para cualquier tipo de corte.',
            'images_path' => json_encode(['https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0hXXSC-K5wKTUK1sla186YCmvF7n5a6Ur3A&s', 'https://cosmeticosnikte.com/wp-content/uploads/2024/04/20240402_195148-scaled.jpg']),
            'duration' => 40,
        ]);

        Service::create([
            'id_provider' => $prestador2->id,
            'service_name' => 'Alisado Vegano de Ácido Hialuronico (Aplica para corto y largo)',
            'service_price' => 45.00,
            'description' => 'Aplica para cabellos hasta 15 cm debajo del hombro',
            'images_path' => json_encode(['https://cloudfront-us-east-1.images.arcpublishing.com/copesa/HJYGCCYNZ5ESPHKJJO2K7YT4KU.jpeg']),
            'duration' => 120,
        ]);
    }
}