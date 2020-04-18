<?php

use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            ['name' => '社長室'],
            ['name' => 'マーケティング'],
            ['name' => 'IT'],
            ['name' => '人事'],
            ['name' => '海外事業'],
            ['name' => '財務']
        ]);
    }
}
