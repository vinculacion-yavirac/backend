<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            CatalogSeeder::class,
            SchoolPeriodSeeder::class,
            ConventionSeeder::class,
            AddressSeeder::class,
            BeneficiaryInstitutionsTableSeeder::class,
            CareersTableSeeder::class,
            ResearchLinesTableSeeder::class,
            SubLineInvestigationsSeeder::class,
            CertificateSeeder::class,
            ResponsibleSeeder::class,
            ProjectsTableSeeder::class,
            GoalSeeder::class,
            ActivitySeeder::class,
            SolicitudSeeder::class,
            DocumentSeeder::class,
            BriefcaseSeeder::class,
            FilesTableSeeder::class,
        ]);
    }
}
