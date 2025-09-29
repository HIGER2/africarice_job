<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'AfricaRice-Cons-It@cgiar.org')->exists()) {
            User::create([
                'name' => 'Douma',
                'last_name'  => 'Armande daniel',
                'email'      => 'AfricaRice-Cons-It@cgiar.org',
                'role'       => 'admin',
            ]);
            User::create([
                'name' => 'Kacou',
                'last_name'  => 'René Christian',
                'email'      => 'c.kacou@cgiar.org',
                'role'       => 'admin',
            ]);

            $this->command->info('Admin par défaut créé avec succès.');
        } else {
            $this->command->info('Admin par défaut déjà existant.');
        }
    }
}
