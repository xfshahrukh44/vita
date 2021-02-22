<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

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

        // USERS
        // admin 1
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@mm.com',
            'password' => Hash::make('temp'),
            'phone' => '12345678',
            'type' => 'superadmin',
        ]);
        // rider 2
        DB::table('users')->insert([
            'name' => 'Rider',
            'email' => 'rider@mm.com',
            'password' => Hash::make('temp'),
            'phone' => '666',
            'type' => 'rider',
        ]);
        // -------------------------------------------------

        // AREAS
        // 1
        DB::table('areas')->insert([
            'name' => 'Gulberg',
        ]);
        // 2
        DB::table('areas')->insert([
            'name' => 'Gulshan',
        ]);
        // 3
        DB::table('areas')->insert([
            'name' => 'Nazimabad',
        ]);
        // -------------------------------------------------
        
        // MARKETS
        // 1
        DB::table('markets')->insert([
            'area_id' => 1,
            'name' => '10 number',
        ]);
        // 2
        DB::table('markets')->insert([
            'area_id' => 1,
            'name' => '12 number',
        ]);
        // 3
        DB::table('markets')->insert([
            'area_id' => 2,
            'name' => 'Maskan',
        ]);
        // 4
        DB::table('markets')->insert([
            'area_id' => 2,
            'name' => 'Gulshan Chowrangi',
        ]);
        // 5
        DB::table('markets')->insert([
            'area_id' => 3,
            'name' => 'Chawla',
        ]);
        // 6
        DB::table('markets')->insert([
            'area_id' => 3,
            'name' => 'Gol Market',
        ]);
        // -------------------------------------------------
        
        // CATEGORIES
        for($i = 1; $i < 9; $i++){
            DB::table('categories')->insert([
                'name' => 'Category '.$i,
            ]);
        }
        // -------------------------------------------------

        // BRANDS
        for($i = 1; $i < 9; $i++){
            DB::table('brands')->insert([
                'name' => 'Brand '.$i,
            ]);
        }
        // -------------------------------------------------

        // UNITS
        for($i = 1; $i < 9; $i++){
            DB::table('units')->insert([
                'name' => 'Unit '.$i,
            ]);
        }
        // -------------------------------------------------
    }
}
