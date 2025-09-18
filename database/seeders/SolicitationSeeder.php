<?php

namespace Database\Seeders;

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

        // Criar algumas solicitações específicas para demonstração
        Solicitation::create([
            'name' => 'João Silva',
            'document' => '12345678901',
            'status' => 'pending',
        ]);

        Solicitation::create([
            'name' => 'Empresa ACME Ltda',
            'document' => '12345678000195',
            'status' => 'approved',
        ]);

        Solicitation::create([
            'name' => 'Maria Santos',
            'document' => '98765432100',
            'status' => 'in_review',
        ]);

        Solicitation::create([
            'name' => 'Tech Solutions S.A.',
            'document' => '11222333000181',
            'status' => 'rejected',
        ]);
    }
}
