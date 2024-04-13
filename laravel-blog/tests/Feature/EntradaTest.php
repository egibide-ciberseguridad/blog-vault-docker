<?php

namespace Tests\Feature;

use App\Models\Entrada;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntradaTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Given
        $entrada = Entrada::factory()->create();

        // When
        $response = $this->get(route('entradas.index'));

        // Then
        $response->assertSuccessful()->assertSee($entrada->titulo);
    }

    /*
    public function testNotAuthNotIndex()
    {
        // Given
        // When
        // Then
        $this->get(route('entradas.index'))
            ->assertRedirect(route('login'));
    }
    */

    public function testCreate()
    {
        // Given
        // When
        $response = $this->get(route('entradas.create'));

        // Then
        $response->assertSuccessful()->assertSeeInOrder(['Nueva entrada', 'Guardar']);
    }

    public function testStore()
    {
        // Given
        $entrada = Entrada::factory()->make();

        // When
        $this->post(route('entradas.store'), $entrada->toArray());

        // Then
        $this->assertEquals(1, Entrada::all()->count());
    }

    public function testStoreRequiresTitulo()
    {
        // Given
        $entrada = Entrada::factory()->make(['titulo' => null]);

        // When
        // Then
        $this->post(route('entradas.store'), $entrada->toArray())
            ->assertSessionHasErrors('titulo');
    }

    public function testShow()
    {
        // Given
        $entrada = Entrada::factory()->create();

        // When
        $response = $this->get(route('entradas.show', $entrada));

        // Then
        $response->assertSuccessful()->assertSee($entrada->titulo);
    }

    public function testEdit()
    {
        // Given
        $entrada = Entrada::factory()->create();

        // When
        $response = $this->get(route('entradas.edit', $entrada), $entrada->toArray());

        // Then
        $response->assertSuccessful()->assertSeeInOrder([$entrada->titulo, 'Guardar']);
    }

    public function testUpdate()
    {
        // Given
        $entrada = Entrada::factory()->create();
        $entrada->titulo = "Actualizada";

        // When
        $this->put(route('entradas.update', $entrada), $entrada->toArray());

        // Then
        $this->assertDatabaseHas('entradas', ['id' => $entrada->id, 'titulo' => $entrada->titulo]);
    }

    public function testDelete()
    {
        // Given
        $entrada = Entrada::factory()->create();

        // When
        $this->delete(route('entradas.destroy', $entrada));

        // Then
        $this->assertDatabaseMissing('entradas', $entrada->toArray());
    }
}
