<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provider;
use App\Models\TimeSlot;
use Carbon\Carbon;

class TimeSlotSeeder extends Seeder
{
    public function run(): void
    {
        $providers = Provider::all();

        $timeRanges = [
            ['08:00', '10:00'],
            ['10:00', '12:00'],
            ['14:00', '16:00'],
            ['16:00', '18:00'],
        ];

        foreach ($providers as $provider) {
            // Créer des créneaux pour les 7 prochains jours
            for ($day = 1; $day <= 7; $day++) {
                $date = Carbon::now()->addDays($day)->toDateString();

                foreach ($timeRanges as $range) {
                    TimeSlot::firstOrCreate([
                        'provider_id' => $provider->id,
                        'date'        => $date,
                        'start_time'  => $range[0],
                        'end_time'    => $range[1],
                    ], [
                        'is_booked' => false,
                    ]);
                }
            }
        }
    }
}