<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specialities = [
            [
                'id' => 1,
                'icon' => 'computer_science.png',
            ],
            [
                'id' => 2,
                'icon' => 'communication_media.png',
            ],
            [
                'id' => 3,
                'icon' => 'business_and_management.png',
            ],
            [
                'id' => 4,
                'icon' => 'engineering.png',
            ],
            [
                'id' => 5,
                'icon' => 'art.png',
            ],
            [
                'id' => 6,
                'icon' => 'agriculture_natural.png',
            ],
            [
                'id' => 7,
                'icon' => 'education.png',
            ],
            [
                'id' => 8,
                'icon' => 'foreign_languages.png',
            ],
            [
                'id' => 9,
                'icon' => 'medicine.png',
            ],
            [
                'id' => 10,
                'icon' => 'health_administration.png',
            ],
            [
                'id' => 11,
                'icon' => 'sciences.png',
            ],
            [
                'id' => 12,
                'icon' => 'tourism.png',
            ],
            [
                'id' => 13,
                'icon' => 'entertainment.png',
            ],
            [
                'id' => 14,
                'icon' => 'calculating.png',
            ],
            [
                'id' => 15,
                'icon' => 'sports.png',
            ],
            [
                'id' => 16,
                'icon' => 'law.png',
            ],
            [
                'id' => 17,
                'icon' => 'restaurant.png',
            ],
        ];

        foreach ($specialities as $speciality) {
            Specialty::whereId($speciality['id'])->update([
                'icon' => $speciality['icon']
            ]);
        }
    }
}
