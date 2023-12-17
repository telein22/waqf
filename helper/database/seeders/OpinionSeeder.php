<?php

namespace Database\Seeders;

use App\Models\Opinion;
use App\Models\User;
use DB;
use Illuminate\Database\Seeder;

class OpinionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $opinions = [
            'ssghando' => 'ساهمت منصة تيلي بسهولة وصولي لجمهوري و تقديم خبراتي ودوراتي عبر المنصة في وقت فراغي دون أي قيود والتزامات إدارية',
            'fahad' => 'منصة تيلي إن فريدة ومن خلالها تم توثيق وتأمين العلاقة مابين المستخدمين، حيث اصبحت تيلي إن الخيار الأمثل لكل أطراف العلاقة سواء خبير أو طالب خدمة.',
            'ahmed' => 'تيلي ان وفرت الفرصة لاصحاب الخبرات لتقديم عصارة خبراتهم بالطريقة و الاسلوب المناسب لهم و للمستفيدين من خبراتهم',
        ];

        $users = User::whereRaw("LOWER(username) IN ('ssghando', 'fahad', 'ahmed')")->get(['id', DB::raw("LOWER(username) as username")]);

        foreach ($opinions as $username => $opinion) {
            $user = $users->where('username', $username)->first();

            if ($user) {
                Opinion::firstOrCreate([
                    'user_id' => $user->id,
                    'opinion' => $opinion,
                ]);
            }
        }
    }
}
