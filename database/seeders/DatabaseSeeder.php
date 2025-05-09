<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\UserRole;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            LocationSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'jon',
            'email' => 'jon@gmail.com',
            'role' => UserRole::SUPER_ADMIN,
            'password' => 'password',
            'village_id' => Village::inRandomOrder()->first()->id,
        ]);

        User::factory()->create([
            'name' => 'snow',
            'email' => 'snow@gmail.com',
            'role' => UserRole::ADMIN,
            'password' => 'password',
            'village_id' => Village::inRandomOrder()->first()->id,
        ]);
        User::factory()->create([
            'name' => 'chris',
            'email' => 'chris@gmail.com',
            'role' => UserRole::ADMIN,
            'password' => 'password',
            'village_id' => Village::inRandomOrder()->first()->id,
        ]);

        $this->call([
            CategorySeeder::class,
        ]);
    }
}
