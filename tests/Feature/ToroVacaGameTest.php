<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ToroVacaGameTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example_crear_nuevo_juego(): void
    {
        $this->assertTrue(true);
        $response = $this->post('/api/game/CrearNuevoJuego');
        $response->assertStatus(400);
        //$response->assertAccepted(404);
        $response->assertJsonStructure([
            'msg',
            'Identificador',
            'status'
        ]);
    }

    public function test_destroy()
    {
        $this->assertTrue(true);
        $response = $this->delete('/api/game/EliminarJuego');
        $response->assertStatus(404);
        //$response->assertAccepted(404);
        $response->assertJsonStructure([
            'msg',
            'status'
        ]);
    }
}
