<?php

namespace Tests\Feature;

use App\Models\Evento;
use App\Models\Pergunta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthZTest extends TestCase
{
    use RefreshDatabase;

    private function cenario(): array
    {
        $dono = User::factory()->create();
        $autor = User::factory()->create();
        $terceiro = User::factory()->create();
        $evento = Evento::create(['user_id' => $dono->id, 'titulo' => 'Evento']);
        $pergunta = Pergunta::create([
            'evento_id' => $evento->id,
            'user_id'   => $autor->id,
            'texto'     => 'Uma pergunta de teste',
        ]);

        return compact('dono', 'autor', 'terceiro', 'evento', 'pergunta');
    }

    private function url($c): string
    {
        return route('eventos.perguntas.destroy', [$c['evento']->id, $c['pergunta']->id]);
    }

    public function test_autor_pode_excluir(): void
    {
        $c = $this->cenario();
        $this->actingAs($c['autor'])->delete($this->url($c))->assertRedirect();
        $this->assertModelMissing($c['pergunta']);
    }

    public function test_dono_do_evento_pode_excluir(): void
    {
        $c = $this->cenario();
        $this->actingAs($c['dono'])->delete($this->url($c))->assertRedirect();
        $this->assertModelMissing($c['pergunta']);
    }

    public function test_terceiro_recebe_403(): void
    {
        $c = $this->cenario();
        $this->actingAs($c['terceiro'])->delete($this->url($c))->assertForbidden();
        $this->assertModelExists($c['pergunta']);
    }

    public function test_visitante_vai_para_login(): void
    {
        $c = $this->cenario();
        $this->delete($this->url($c))->assertRedirect(route('login'));
        $this->assertModelExists($c['pergunta']);
    }

    public function test_botao_aparece_so_para_quem_pode(): void
    {
        $c = $this->cenario();
        $this->actingAs($c['terceiro'])->get(route('eventos.show', $c['evento']->id))->assertDontSee('Excluir');
        $this->actingAs($c['autor'])->get(route('eventos.show', $c['evento']->id))->assertSee('Excluir');
    }
}
