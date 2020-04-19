<?php

use Illuminate\Database\Seeder;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 2; $i <= 11; $i++) {
            DB::table('user_group')->insert([
                'user_id' => $i,
                'group_id' => 1
            ]);
        }
        for ($i = 12; $i <= 31; $i++) {
            DB::table('user_group')->insert([
                'user_id' => $i,
                'group_id' => 2
            ]);
        }
        for ($i = 32; $i <= 61; $i++) {
            DB::table('user_group')->insert([
                'user_id' => $i,
                'group_id' => 3
            ]);
        }
        for ($i = 62; $i <= 71; $i++) {
            DB::table('user_group')->insert([
                'user_id' => $i,
                'group_id' => 4
            ]);
        }
        for ($i = 72; $i <= 81; $i++) {
            DB::table('user_group')->insert([
                'user_id' => $i,
                'group_id' => 5
            ]);
        }
        for ($i = 82; $i <= 101; $i++) {
            DB::table('user_group')->insert([
                'user_id' => $i,
                'group_id' => 6
            ]);
        }
    }
}
