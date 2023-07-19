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
            'email' => 'hector@gmail.com',
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
            'names' => 'Steven',
            'last_names' => 'Guerra',
        ]);


        $userTre = User::create([
            'email' => 'steven@gmail.com',
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
            'email' => 'oscar@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $personFour->id,
        ]);
        $userFour->assignRole('Estudiante');

        $personFive = Person::create([
            'identification_type' => 'Cédula',
            'identification' => '175516835',
            'names' => 'Mac',
            'last_names' => 'de Marco',
        ]);


        $userFive = User::create([
            'email' => 'mac@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $personFive->id,
        ]);
        $userFive->assignRole('Estudiante');

        $personSix = Person::create([
            'identification_type' => 'Cédula',
            'identification' => '175516825',
            'names' => 'Gustavo',
            'last_names' => 'Cerati',
        ]);

        $userSix = User::create([
            'email' => 'gustavo@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $personSix->id,
        ]);
        $userSix->assignRole('Estudiante');


        $personSeven = Person::create([
            'identification_type' => 'Cédula',
            'identification' => '175516845',
            'names' => ' kurt',
            'last_names' => 'Cobain',
        ]);

        $userSeven = User::create([
            'email' => 'kurt@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $personSeven->id,
        ]);
        $userSeven->assignRole('Estudiante');


        $personEigth = Person::create([
            'identification_type' => 'Cédula',
            'identification' => '175516804',
            'names' => 'Amy',
            'last_names' => 'Winehouse',
        ]);

        $userEigth = User::create([
            'email' => 'amy@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $personEigth->id,
        ]);
        $userEigth->assignRole('Estudiante');

        $personNine = Person::create([
            'identification_type' => 'Cédula',
            'identification' => '1752673101',
            'names' => 'Brayan',
            'last_names' => 'Ganan',
        ]);

        $userNine = User::create([
            'email' => 'brayan@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $personNine->id,
        ]);
        $userNine->assignRole('Coordinador Carrera');


        $personTen = Person::create([
            'identification_type' => 'Cédula',
            'identification' => '1203269954',
            'names' => 'Geovanny',
            'last_names' => 'Murillo',
        ]);

        $userTen = User::create([
            'email' => 'murillo@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $personTen->id,
        ]);
        $userTen->assignRole('Docente Tutor');


        $personEleven = Person::create([
            'identification_type' => 'Cédula',
            'identification' => '1203269894',
            'names' => 'Luis',
            'last_names' => 'Cunalata',
        ]);

        $userEleven = User::create([
            'email' => 'luis@gmail.com',
            'password' => Hash::make('yavirac1810'),
            'person' => $personEleven->id,
        ]);
        $userEleven->assignRole('Docente Tutor');


        $personSeventeen = Person::create([
            'identification_type' => 'Cédula',
            'identification' => '1729081636',
            'names' => 'Andres',
            'last_names' => 'Chipantasi',
        ]);

        $userSeventeen = User::create([
            'email' => 'chipantasi@gmail.com',
            'password' => Hash::make('0508023'),
            'person' => $personSeventeen->id,
        ]);
        $userSeventeen->assignRole('Coordinador General');

    }
}
