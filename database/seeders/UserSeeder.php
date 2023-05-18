<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Person;
use App\Models\Catalog;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $person = Person::create([
            'identification_type' => 'Cédula',
            'identification' => '1111111111',
            'names' => 'Asi',
            'last_names' => 'Yavirac',
        ]);

        $user = User::create([
            'email' => 'yavirac@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $person->id,
        ]);
        $user->assignRole('Super Administrador');
    }
}
