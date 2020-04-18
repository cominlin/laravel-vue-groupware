<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'kana' => 'スーパー アドミン',
            'email' => env('ADMIN_EMAIL'),
            'password' => bcrypt(env('ADMIN_PASSWORD')),
            'type' => 3,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        for($i = 1; $i <= 100; $i++) {
            DB::table('users')->insert([
                'name' => '山田 太郎'. $i,
                'kana' => 'やまだ たろう',
                'email' => 'test' . $i . '@test.com',
                'password' => bcrypt('123123'),
                'type' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        DB::table('users')->insert([
            'name' => '退職 太郎',
            'kana' => '退職 たろう',
            'email' => 'retire@test.com',
            'password' => bcrypt('123123'),
            'type' => 0,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
