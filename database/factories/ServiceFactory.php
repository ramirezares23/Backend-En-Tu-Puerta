<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $verbs = ['Corte', 'Poda', 'Limpieza', 'Diseño', 'Reparación', 'Instalación', 'Maquillaje', 'Alisado', 'Coloración'];
        $nouns = ['Cabello', 'Árboles', 'Uñas', 'Pestañas', 'Cejas', 'Jardín', 'Oficina', 'Alfombra', 'Piel'];
        $adjectives = ['Express', 'Profesional', 'Semipermanente', 'Intensivo', 'Completo', 'Básico', 'Premium'];


        $images = [
            ['https://st.depositphotos.com/1004918/4395/i/450/depositphotos_43950709-stock-photo-at-the-hairdressers.jpg',
            'https://www.capilarea.com/wp-content/uploads/2023/01/Pelo-liquido.jpeg'],

            ['https://rootsmacaronesia.com/wp-content/uploads/2022/07/Beneficios-de-la-poda-de-arboles-frutales-en-verano.jpeg',
            'https://cdn.manomano.com/media/edison/6/4/2/4/6424712558db.jpg'],

            ['https://hips.hearstapps.com/hmg-prod/images/manicura-francesa-unas-cortas-674cc0c20f03e.jpg?crop=0.670xw:1.00xh;0.0294xw,0&resize=980:*',
            'https://content20.lecturas.com/medio/2024/11/26/15-ideas-de-manicura-elegantes-para-estas-fiestas_bb54407d_241126191405_1280x720.webp'],

            ['https://clinicanuevacaracas.com/wp-content/uploads/2024/08/Pestanas-extensiones.jpg',
            'https://makeupmestudio.com/wp-content/uploads/2019/01/maxresdefault-1024x576.jpg'],

            ['https://studio3dbrows.com/ve/wp-content/uploads/2021/04/ombre-800x800.jpg',
            'https://cdn-612bf643c1ac18b2a0343318.closte.com/wp-content/uploads/2022/05/laminado-cejas-brow-lamination-instabrows-1024x1024.jpg'],

            ['https://www.areaverde.com.ve/wp-content/uploads/2022/07/FINAL-DESMALEZADOR.jpg',
            'https://vanbeek.pe/cdn/shop/articles/xx2_800x.jpg?v=1697691560'],
            
            ['https://www.reparaciondesillasparaoficina.com.mx/reparacion-de-sillas-secretariales/reparacion-de-sillas-secretariales-en-cdmx.jpg',
            'https://www.officemadrid.es//wp-content/uploads/servicios-tecnologicos-en-tu-oficina.jpg'],

            ['https://latiendadelasalfombras.com/cdn/shop/files/Intalacionlatiendadelasalfombras.jpg?v=1694459653',
            'https://static.vecteezy.com/system/resources/previews/047/902/886/non_2x/professional-carpet-cleaning-service-in-action-photo.jpg'],

            ['https://fisiknova.com.mx/wp-content/uploads/2021/02/facial4.jpg',
            'https://luxsense.com.mx/wp-content/uploads/2022/05/Imagen.jpg']
        ];
        // Generar un nombre de servicio aleatorio
        $verbService = fake()->randomElement($verbs);
        $nounService = fake()->randomElement($nouns);

        $serviceName = $verbService . ' de ' . $nounService;
        if (fake()->boolean(50)) { // 50% de probabilidad de agregar un adjetivo
            $serviceName .= ' ' . fake()->randomElement($adjectives);
        }

        if($nounService == 'Cabello'){
            $images_path = $images[0];
        }elseif($nounService == 'Árboles'){
            $images_path = $images[1];
        }elseif($nounService == 'Uñas'){
            $images_path = $images[2];
        }elseif($nounService == 'Pestañas'){
            $images_path = $images[3];
        }elseif($nounService == 'Cejas'){
            $images_path = $images[4];
        }elseif($nounService == 'Jardín'){
            $images_path = $images[5];
        }elseif($nounService == 'Oficina'){
            $images_path = $images[6];
        }elseif($nounService == 'Alfombra'){
            $images_path = $images[7];
        }elseif($nounService == 'Piel'){
            $images_path = $images[8];
        }

        return [
            'id_provider' => User::factory(),
            'service_name' => $serviceName,
            'service_price' => fake()->randomFloat(2, 1),
            'description' => fake()->text(),
            'images_path' => json_encode($images_path),
            'duration' => fake()->numberBetween(10,300),
        ];
    }
}
