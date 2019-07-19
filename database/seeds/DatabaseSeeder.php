<?php

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
        DB::table('users')->insert([
            'store_id' => 1,
            'role' => 1,
            'name' => "두잇두잇",
            'category' => "자바",
            'phone' => "01044633474",
            'email' => "dkreka@naver.com",
            'password' => bcrypt("123456"),
        ]);
    }
}
