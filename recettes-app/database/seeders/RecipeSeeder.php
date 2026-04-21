<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = [
            [
                'title' => 'Salade César',
                'description' => 'Une délicieuse salade fraîche et croquante.',
                'category' => 'Entrée',
                'prep_time' => 15,
                'servings' => 2,
                'total_calories' => 450,
                'ingredients' => [
                    ['name' => 'laitue', 'quantity' => 200, 'unit' => 'g', 'calories_per_100g' => 15],
                    ['name' => 'poulet', 'quantity' => 150, 'unit' => 'g', 'calories_per_100g' => 165],
                    ['name' => 'croûtons', 'quantity' => 50, 'unit' => 'g', 'calories_per_100g' => 400],
                ]
            ],
            [
                'title' => 'Poulet Basquaise',
                'description' => 'Un classique de la cuisine française aux poivrons et tomates.',
                'category' => 'Plat',
                'prep_time' => 60,
                'servings' => 4,
                'total_calories' => 1600,
                'ingredients' => [
                    ['name' => 'poulet', 'quantity' => 800, 'unit' => 'g', 'calories_per_100g' => 165],
                    ['name' => 'poivron', 'quantity' => 400, 'unit' => 'g', 'calories_per_100g' => 20],
                    ['name' => 'tomate', 'quantity' => 400, 'unit' => 'g', 'calories_per_100g' => 18],
                ]
            ],
            [
                'title' => 'Tarte aux Pommes',
                'description' => 'Tarte traditionnelle avec une pâte brisée maison.',
                'category' => 'Dessert',
                'prep_time' => 45,
                'servings' => 6,
                'total_calories' => 2200,
                'ingredients' => [
                    ['name' => 'pomme', 'quantity' => 600, 'unit' => 'g', 'calories_per_100g' => 52],
                    ['name' => 'farine', 'quantity' => 250, 'unit' => 'g', 'calories_per_100g' => 364],
                    ['name' => 'beurre', 'quantity' => 125, 'unit' => 'g', 'calories_per_100g' => 717],
                ]
            ],
            [
                'title' => 'Omelette aux Champignons',
                'description' => 'Rapide et nutritive, parfaite pour un repas léger.',
                'category' => 'Plat',
                'prep_time' => 10,
                'servings' => 1,
                'total_calories' => 320,
                'ingredients' => [
                    ['name' => 'oeuf', 'quantity' => 120, 'unit' => 'g', 'calories_per_100g' => 155],
                    ['name' => 'champignon', 'quantity' => 100, 'unit' => 'g', 'calories_per_100g' => 22],
                    ['name' => 'beurre', 'quantity' => 10, 'unit' => 'g', 'calories_per_100g' => 717],
                ]
            ],
            [
                'title' => 'Mousse au Chocolat',
                'description' => 'Un dessert riche et onctueux au chocolat noir.',
                'category' => 'Dessert',
                'prep_time' => 20,
                'servings' => 4,
                'total_calories' => 1800,
                'ingredients' => [
                    ['name' => 'chocolat noir', 'quantity' => 200, 'unit' => 'g', 'calories_per_100g' => 546],
                    ['name' => 'oeuf', 'quantity' => 240, 'unit' => 'g', 'calories_per_100g' => 155],
                    ['name' => 'sucre', 'quantity' => 50, 'unit' => 'g', 'calories_per_100g' => 387],
                ]
            ]
        ];

        foreach ($recipes as $r) {
            $recipe = \App\Models\Recipe::create([
                'title' => $r['title'],
                'description' => $r['description'],
                'category' => $r['category'],
                'prep_time' => $r['prep_time'],
                'servings' => $r['servings'],
                'total_calories' => $r['total_calories'],
            ]);

            foreach ($r['ingredients'] as $i) {
                $recipe->ingredients()->create($i);
            }
        }
    }
}
