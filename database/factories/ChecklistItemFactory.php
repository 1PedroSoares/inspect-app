<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ChecklistItem;
use App\Models\Inspection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChecklistItem>
 */
class ChecklistItemFactory extends Factory
{
    /**
     * O model correspondente da factory.
     *
     * @var string
     */
    protected $model = ChecklistItem::class;

    /**
     * Define o estado padrão do model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //Define um id padrão.
            'inspection_id' => Inspection::factory(),

            'descricao' => $this->faker->sentence(6),
            'obrigatorio' => $this->faker->boolean(50), // 50% de chance de ser true
            'concluido' => false, // Padrão
        ];
    }
}
