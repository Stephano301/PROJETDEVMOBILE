<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name'        => 'Plomberie',
                'category'    => 'Plomberie',
                'description' => 'Réparation de fuites, installation sanitaire, débouchage.',
                'icon'        => 'plomberie',
                'base_price'  => 25000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Ménage',
                'category'    => 'Nettoyage',
                'description' => 'Nettoyage complet de votre domicile.',
                'icon'        => 'menage',
                'base_price'  => 15000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Électricité',
                'category'    => 'Électricité',
                'description' => 'Installation, réparation et mise aux normes électrique.',
                'icon'        => 'electricite',
                'base_price'  => 30000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Jardinage',
                'category'    => 'Jardinage',
                'description' => 'Tonte, taille, entretien de jardin.',
                'icon'        => 'jardinage',
                'base_price'  => 20000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Peinture',
                'category'    => 'Peinture',
                'description' => 'Peinture intérieure et extérieure, ravalement.',
                'icon'        => 'peinture',
                'base_price'  => 35000,
                'is_active'   => true,
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}