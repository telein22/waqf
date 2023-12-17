<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'key' => 'faq',
                'value' => json_encode([
                    'ar' => [
                            'التسجيل في منصة تيلي ان',
                            'البحث عن التخصصات المختلفة ',
                    ],
                    'en' => [
                            'How to Sign in or register in the app',
                            'Search for the specialities you are interested in',
                    ],
                ]),
                'updated_at' => time(),
            ],
            [
                'key' => 'rules',
                'value' => json_encode([
                    'ar' => [
                        'rule 1 in Arabic',
                        'rule 2 in Arabic',
                    ],
                    'en' => [
                        'Rule 1 in English',
                        'Rule 2 in English',
                    ],
                ]),
                'updated_at' => time(),
            ],
            [
                'key' => 'terms',
                'value' => json_encode([
                'ar' => 'شروط الاستخدام',
                'en' => 'Terms of Use',
                ]),
                'updated_at' => time(),
            ],
        ];

        Setting::insert($settings);
    }
}
