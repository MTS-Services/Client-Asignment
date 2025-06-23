<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            AdminSeeder::class,
            RoleHasPermissionSeeder::class,
            ApplicationSettingSeeder::class,
            CategorySeeder::class,
            PublisherSeeder::class,
            AuthorSeeder::class,
            RackSeeder::class,
            MagazineSeeder::class,
            NewspaperSeeder::class,
            BookSeeder::class,
            BookIssuesSeeder::class,
        ]);
    }
}
