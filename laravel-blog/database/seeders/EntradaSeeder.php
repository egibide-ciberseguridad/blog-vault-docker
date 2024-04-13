<?php

namespace Database\Seeders;

use App\Models\Comentario;
use App\Models\Entrada;
use Illuminate\Database\Seeder;

class EntradaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $entrada = Entrada::factory()->create();

            $num_comentarios = rand(0, 3);

            for ($j = 0; $j < $num_comentarios; $j++) {
                Comentario::factory()->create([
                    'entrada_id' => $entrada->id,
                ]);
            }
        }
    }
}
