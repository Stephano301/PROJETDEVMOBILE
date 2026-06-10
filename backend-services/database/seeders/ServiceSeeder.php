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
                'icon'        => 'ic_cat_plumbing',
                'base_price'  => 25000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Ménage',
                'category'    => 'Nettoyage',
                'description' => 'Nettoyage complet de votre domicile.',
                'icon'        => 'ic_cat_cleaning',
                'base_price'  => 15000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Électricité',
                'category'    => 'Électricité',
                'description' => 'Installation, réparation et mise aux normes électrique.',
                'icon'        => 'ic_cat_electricity',
                'base_price'  => 30000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Jardinage',
                'category'    => 'Jardinage',
                'description' => 'Tonte, taille, entretien de jardin.',
                'icon'        => 'ic_cat_gardening',
                'base_price'  => 20000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Peinture',
                'category'    => 'Peinture',
                'description' => 'Peinture intérieure et extérieure, ravalement.',
                'icon'        => 'ic_cat_peinture',
                'base_price'  => 35000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Déménagement',
                'category'    => 'Déménagement',
                'description' => 'Transport et déménagement de meubles et affaires.',
                'icon'        => 'ic_cat_demenagement',
                'base_price'  => 50000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Sécurité',
                'category'    => 'Sécurité',
                'description' => 'Installation de caméras, alarmes et systèmes de sécurité.',
                'icon'        => 'ic_cat_securite',
                'base_price'  => 40000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Climatisation',
                'category'    => 'Climatisation',
                'description' => 'Installation et entretien de climatiseurs.',
                'icon'        => 'ic_cat_climatisation',
                'base_price'  => 45000,
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