<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Inspection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inspection>
 */
class InspectionFactory extends Factory
{
    /**
     * O model correspondente da factory.
     *
     * @var string
     */
    protected $model = Inspection::class;

    /**
     * Define o estado padrão do model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(4), // Gera uma frase curta aleatória.
            'cep' => $this->faker->numerify('#####-###'), // Formato 12345-678.
            'logradouro' => $this->faker->streetName(),
            'numero' => $this->faker->buildingNumber(),
            'bairro' => $this->faker->word(), // Word para substituir o Bairro, para testes.
            'cidade' => $this->faker->city(),
            'uf' => $this->faker->stateAbbr(),
            'data_prevista' => $this->faker->dateTimeBetween('+1 week', '+1 month'), // Data futura.
            'status' => 'Pendente', // Padrão.
        ];
    }
}
