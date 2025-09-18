<?php

namespace Database\Seeders;

use App\Models\Stage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = [
            [
                'name' => 'Análise Inicial',
                'description' => 'Primeira análise da solicitação para verificar documentação e requisitos básicos',
                'sla' => 24, // 24 horas
            ],
            [
                'name' => 'Verificação de Documentos',
                'description' => 'Validação de toda documentação necessária e conformidade legal',
                'sla' => 48, // 48 horas
            ],
            [
                'name' => 'Análise Técnica',
                'description' => 'Análise técnica detalhada da viabilidade da solicitação',
                'sla' => 72, // 72 horas
            ],
            [
                'name' => 'Aprovação Final',
                'description' => 'Aprovação final pela equipe responsável e encaminhamento',
                'sla' => 24, // 24 horas
            ],
            [
                'name' => 'Concluído',
                'description' => 'Solicitação processada e finalizada com sucesso',
                'sla' => 0, // Não há SLA para etapa final
            ],
        ];

        foreach ($stages as $stage) {
            Stage::create($stage);
        }
    }
}
