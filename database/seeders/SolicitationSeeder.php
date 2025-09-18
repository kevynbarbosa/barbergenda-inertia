<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Solicitation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SolicitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar 50 solicitações usando a factory
        Solicitation::factory(50)->create();

        // Criar algumas pessoas e solicitações específicas para demonstração
        $pessoa1 = Person::create([
            'name' => 'João Silva',
            'document' => '12345678901',
        ]);

        Solicitation::create([
            'person_id' => $pessoa1->id,
            'status' => 'pending',
            'stage_id' => 1,
        ]);

        $pessoa2 = Person::create([
            'name' => 'Empresa ACME Ltda',
            'document' => '12345678000195',
        ]);

        Solicitation::create([
            'person_id' => $pessoa2->id,
            'status' => 'approved',
            'stage_id' => 5,
        ]);

        $pessoa3 = Person::create([
            'name' => 'Maria Santos',
            'document' => '98765432100',
        ]);

        Solicitation::create([
            'person_id' => $pessoa3->id,
            'status' => 'in_review',
            'stage_id' => 3,
        ]);

        $pessoa4 = Person::create([
            'name' => 'Tech Solutions S.A.',
            'document' => '11222333000181',
        ]);

        Solicitation::create([
            'person_id' => $pessoa4->id,
            'status' => 'rejected',
            'stage_id' => 2,
        ]);
    }
}
