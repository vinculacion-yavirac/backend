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
            'names' => 'Asistente Web',
            'last_names' => 'Yavirac',
        ]);

        $user = User::create([
            'email' => 'yavirac@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $person->id,
        ]);
        $user->assignRole('Super Administrador');


        $personOne = Person::create([
            'identification_type' => 'Cédula',
            'identification' => '175516802',
            'names' => 'Hector Steveen',
            'last_names' => 'Ordoñez',
        ]);


        $userOne = User::create([
            'email' => 'zh311505@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $personOne->id,
        ]);
        $userOne->assignRole('Docente Vinculación');


        $personTwo = Person::create([
            'identification_type' => 'Cédula',
            'identification' => '175516802',
            'names' => 'Rodolfo',
            'last_names' => 'Vera',
        ]);


        $userTwo = User::create([
            'email' => 'rodolfo@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $personTwo->id,
        ]);
        $userTwo->assignRole('Docente Tutor');



        $personTre = Person::create([
            'identification_type' => 'Cédula',
            'identification' => '175516806',
            'names' => 'Alejandra',
            'last_names' => 'Vera',
        ]);


        $userTre = User::create([
            'email' => 'Alejandra@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $personTre->id,
        ]);
        $userTre->assignRole('Estudiante');


        $personFour = Person::create([
            'identification_type' => 'Cédula',
            'identification' => '175516806',
            'names' => 'Oscar',
            'last_names' => 'Nogales',
        ]);


        $userFour = User::create([
            'email' => 'Oscar@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $personFour->id,
        ]);
        $userFour->assignRole('Estudiante');




    }
}
