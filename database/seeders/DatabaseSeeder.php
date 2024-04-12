<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Iksan Fauzi Nugraha',
            'email' => 'iksanfauzi727@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $departments = [
            ['name' => 'MBA', 'active' => true],
            ['name' => 'RCS', 'active' => true],
            ['name' => 'ZBS', 'active' => true],
            ['name' => 'VSI', 'active' => true],
        ];
        foreach ($departments as $department) {
            Department::create($department);
        }

        $position = [
            ['name' => 'Team IT AS'],
            ['name' => 'Team IMP'],
            ['name' => 'Team Payment'],
            ['name' => 'Team Pemetaan'],
            ['name' => 'Team Devel'],
        ];

        foreach ($position as $item) {
            Position::create($item);
        }

        Employee::factory(20)->create();

    }
}
