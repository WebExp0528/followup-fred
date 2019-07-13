<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Frequency;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('frequencies')->delete();
        DB::table('users')->delete();
        DB::table('auth_tokens')->delete();
        DB::table('emails')->delete();
        DB::table('followups')->delete();
        Frequency::create(["hours"=> 8, "minutes" => 0]);
        Frequency::create(["hours"=> 16,"minutes" => 0]);
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Test user',
            'timezone' => "America/Argentina/Buenos_Aires",
            'email' => 'goldtime0723@gmail.com'
        ]);
        DB::table('auth_tokens')->insert([
            'id' => 1,
            'access_token' => 'ya29.GlsQB-klYlbiXwC14CuH0I0Oz3LMTB7uq9fZoywmQ8WAX6LIONeHNNzsbQiUVm6wj7BMndQZk1XpJ9a_N7g4BVkUr1SQhZpOhWiLhaWC7Cmy9i6fuEWc1tCh_saT',
            'refresh_token' => '1/MPuU8KDOLjFU1wuhm6PnHNn9suC7qqvVFMZA2RTS5iVItBJOgMQ7k4IrzBjT3tOD',
            'email' => 'goldtime0723@gmail.com'
        ]);
        DB::table('emails')->insert([
            'id' => 1,
            'to' => 'sebastiancor7218@gmail.com',
            'subject' => 'Test subject',
            'body' => 'Test body'
        ]);
        DB::table('followup_contents')->insert([
            'index' => 1,
            'content' => 'Hey, I just wanted to follow up on my last message, then have the actual email body below',
        ]);
        DB::table('followup_contents')->insert([
            'index' => 2,
            'content' => 'Hey, I wanted to check in and see if you had any questions over my last email,  then have the actual email body below',
        ]);
        DB::table('followup_contents')->insert([
            'index' => 3,
            'content' => 'Hey, I wanted to see if you have been receiving my emails?',
        ]);
        DB::table('followup_contents')->insert([
            'index' => 4,
            'content' => 'Hi, I have sent a few emails and just wanted to make sure they were not going into your spam folder.',
        ]);
    }
}
