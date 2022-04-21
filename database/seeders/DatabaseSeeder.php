<?php

namespace Database\Seeders;

use App\Models\Folder;
use App\Models\Location;
use App\Models\Share;
use App\Models\Token;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory(10)->create();

        for ($i = 0; $i < 10; $i++){
            $user = $users[$i];
            var_dump($user);
            Token::factory()->create([
                "user_id" => $user->uuid
            ]);
            Folder::factory(2)->create([
                "user_id" => $user->uuid
            ]);
            $locs = Location::factory(10)->create([
                "user_id" => $user->uuid
            ]);
            $s = ($i+2>9)?$i+2-10:$i+2;
            for ($j = 0; $j < 3; $j++){
                Share::factory()->create([
                    "sender_id" => $user->uuid,
                    "recipient_id" => $users[$s]->uuid,
                    "resource_id" => $locs[$j]->id
                ]);
            }
        }
    }
}
