<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            [
                'name'        => 'Jean Rakoto',
                'email'       => 'jean.rakoto@email.com',
                'phone'       => '034 12 345 67',
                'city'        => 'Antananarivo',
                'bio'         => 'Plombier professionnel avec 10 ans d\'expérience.',
                'hourly_rate' => 25000,
                'rating'      => 4.8,
                'services'    => ['Plomberie'],
            ],
            [
                'name'        => 'Marie Rasoa',
                'email'       => 'marie.rasoa@email.com',
                'phone'       => '033 98 765 43',
                'city'        => 'Antananarivo',
                'bio'         => 'Spécialiste en nettoyage et entretien de maison.',
                'hourly_rate' => 15000,
                'rating'      => 4.9,
                'services'    => ['Ménage'],
            ],
            [
                'name'        => 'Paul Andry',
                'email'       => 'paul.andry@email.com',
                'phone'       => '032 11 222 33',
                'city'        => 'Antananarivo',
                'bio'         => 'Électricien certifié, installation et dépannage.',
                'hourly_rate' => 30000,
                'rating'      => 4.7,
                'services'    => ['Électricité'],
            ],
            [
                'name'        => 'Haja Ramiandrisoa',
                'email'       => 'haja.rami@email.com',
                'phone'       => '034 55 666 77',
                'city'        => 'Antananarivo',
                'bio'         => 'Jardinier passionné, entretien et aménagement.',
                'hourly_rate' => 20000,
                'rating'      => 4.6,
                'services'    => ['Jardinage'],
            ],
            [
                'name'        => 'Fara Rabemanantsoa',
                'email'       => 'fara.rabe@email.com',
                'phone'       => '033 77 888 99',
                'city'        => 'Antananarivo',
                'bio'         => 'Peintre qualifiée, intérieur et extérieur.',
                'hourly_rate' => 35000,
                'rating'      => 4.5,
                'services'    => ['Peinture'],
            ],
            [
                'name'        => 'Lova Rakotondrabe',
                'email'       => 'lova.rako@email.com',
                'phone'       => '032 44 555 66',
                'city'        => 'Antananarivo',
                'bio'         => 'Multi-services : plomberie et électricité.',
                'hourly_rate' => 28000,
                'rating'      => 4.4,
                'services'    => ['Plomberie', 'Électricité'],
            ],
            [
                'name'        => 'Nirina Razafindrakoto',
                'email'       => 'nirina.raza@email.com',
                'phone'       => '034 33 444 55',
                'city'        => 'Antananarivo',
                'bio'         => 'Spécialiste ménage et jardinage.',
                'hourly_rate' => 18000,
                'rating'      => 4.3,
                'services'    => ['Ménage', 'Jardinage'],
            ],
            [
                'name'        => 'Tojo Randrianarisoa',
                'email'       => 'tojo.rand@email.com',
                'phone'       => '033 22 333 44',
                'city'        => 'Antananarivo',
                'bio'         => 'Peintre et rénovateur expérimenté.',
                'hourly_rate' => 32000,
                'rating'      => 4.7,
                'services'    => ['Peinture'],
            ],
            [
                'name'        => 'Vola Andriantsoa',
                'email'       => 'vola.andri@email.com',
                'phone'       => '032 66 777 88',
                'city'        => 'Antananarivo',
                'bio'         => 'Électricienne diplômée, disponible 7j/7.',
                'hourly_rate' => 28000,
                'rating'      => 4.9,
                'services'    => ['Électricité'],
            ],
            [
                'name'        => 'Aina Rafaralahy',
                'email'       => 'aina.rafa@email.com',
                'phone'       => '034 88 999 00',
                'city'        => 'Antananarivo',
                'bio'         => 'Plombier rapide et efficace.',
                'hourly_rate' => 22000,
                'rating'      => 4.2,
                'services'    => ['Plomberie'],
            ],
        ];

        foreach ($providers as $data) {
            // Créer le user
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make('password'),
                ]
            );

            // Assigner le rôle prestataire
            $user->assignRole('prestataire');

            // Créer le profil provider
            $provider = Provider::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'phone'        => $data['phone'],
                    'city'         => $data['city'],
                    'bio'          => $data['bio'],
                    'hourly_rate'  => $data['hourly_rate'],
                    'rating'       => $data['rating'],
                    'is_available' => true,
                    'is_verified'  => true,
                ]
            );

            // Attacher les services
            $serviceIds = Service::whereIn('name', $data['services'])
                ->pluck('id')
                ->toArray();
            $provider->services()->sync($serviceIds);
        }

        // Créer aussi un client et un admin de test
        $client = User::firstOrCreate(
            ['email' => 'client@test.com'],
            ['name' => 'Client Test', 'password' => Hash::make('password')]
        );
        $client->assignRole('client');

        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );
        $admin->assignRole('admin');
    }
}