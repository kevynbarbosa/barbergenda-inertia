<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Solicitation>
 */
class SolicitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $documentType = $this->faker->randomElement(['cpf', 'cnpj']);

        return [
            'name' => $documentType === 'cpf'
                ? $this->faker->name()
                : $this->faker->company(),
            'document' => $documentType === 'cpf'
                ? $this->generateCPF()
                : $this->generateCNPJ(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected', 'in_review']),
            'stage_id' => $this->faker->numberBetween(1, 5), // Assumes 5 stages from seeder
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Gera um CPF válido
     */
    private function generateCPF(): string
    {
        $cpf = '';
        for ($i = 0; $i < 9; $i++) {
            $cpf .= $this->faker->numberBetween(0, 9);
        }

        // Calcula primeiro dígito verificador
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int)$cpf[$i] * (10 - $i);
        }
        $digit1 = 11 - ($sum % 11);
        $digit1 = $digit1 >= 10 ? 0 : $digit1;

        // Calcula segundo dígito verificador
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int)$cpf[$i] * (11 - $i);
        }
        $sum += $digit1 * 2;
        $digit2 = 11 - ($sum % 11);
        $digit2 = $digit2 >= 10 ? 0 : $digit2;

        return $cpf . $digit1 . $digit2;
    }

    /**
     * Gera um CNPJ válido
     */
    private function generateCNPJ(): string
    {
        $cnpj = '';
        for ($i = 0; $i < 12; $i++) {
            $cnpj .= $this->faker->numberBetween(0, 9);
        }

        // Calcula primeiro dígito verificador
        $weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int)$cnpj[$i] * $weights1[$i];
        }
        $digit1 = $sum % 11 < 2 ? 0 : 11 - ($sum % 11);

        // Calcula segundo dígito verificador
        $weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int)$cnpj[$i] * $weights2[$i];
        }
        $sum += $digit1 * $weights2[12];
        $digit2 = $sum % 11 < 2 ? 0 : 11 - ($sum % 11);

        return $cnpj . $digit1 . $digit2;
    }
}
