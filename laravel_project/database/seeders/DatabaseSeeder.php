<?php


namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed categories and challenges using the ChallengeHubSeeder
        // This includes: Admin user, UserAdmin accounts, regular users, categories, and all challenges
        $this->call(ChallengeHubSeeder::class);
    }
}
