<?php

namespace Database\Seeders;

use App\Models\Publication;
use App\Models\Recrutement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobPublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer 5 jobs (recrutements)
        for ($i = 1; $i <= 10; $i++) {
            var_dump($i);
            $job = Recrutement::create([
                'uuid' => (string) Str::uuid(),
                'code_recrutement' => 'JOB-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'center' => 'Center ' . $i,
                'country_duty_station' => 'Côte d\'Ivoire',
                'city_duty_station' => 'Abidjan',
                'position_title' => 'Position ' . $i,
                'recrutement_id' => 'REC-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'contract_from' => now()->subMonths(rand(1, 12)),
                'contract_to' => now()->addMonths(rand(12, 24)),
                'date' => now(),
                'grade' => 'Grade ' . $i,
                'division' => 'Division ' . $i,
                'program' => 'Program ' . $i,
                'sub_unit' => 'SubUnit ' . $i,
                'education_level' => 'Master',
                'resources_type' => 'Type ' . $i,
                'contract_time' => 'full_time',
                'is_active' => 'active',
                'supervisor_name' => 'Supervisor ' . $i,
                'supervisor_position' => 'Position ' . $i,
                'recruitment_type' => 'Type ' . $i,
                'country_of_recruitment' => 'Côte d\'Ivoire',
                'cgiar_workforce_group' => 'Group ' . $i,
                'cgiar_group' => 'CG Group ' . $i,
                'cgiar_appointed' => 'yes',
                'percent_time_other_center' => rand(0, 50),
                'shared_working_arrangement' => 'yes',
                'initiative_lead' => 'Lead ' . $i,
                'initiative_personnel' => 'Personnel ' . $i,
                'salary_post' => rand(100000, 500000),
            ]);

            // Créer 1 ou 2 publications pour chaque job
            $numPublications = rand(1, 2);
            for ($j = 1; $j <= $numPublications; $j++) {
                Publication::create([
                    'uuid' => (string) Str::uuid(),
                    'reference' => 'PUB-' . $i . '-' . $j,
                    'type' => ['internal', 'public'][array_rand(['internal', 'public'])],
                    'recrutement_id' => $job->id,
                    // 'published_by' => $userIds[array_rand($userIds)],
                    'is_published' => rand(0, 1) == 1,
                    'is_closed' => rand(0, 1) == 1,
                    'published_at' => now()->subDays(rand(1, 30)),
                    'expires_at' => now()->addDays(rand(30, 90)),
                ]);
            }
        }
    }
}
