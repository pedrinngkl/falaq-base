<?php

namespace Tests\Feature;

use App\Models\Evento;
use App\Models\Pergunta;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UpvotePerguntaTest extends TestCase
{
    use RefreshDatabase;

    private function pergunta(string $texto = 'Pergunta de teste'): Pergunta
    {
        $evento = Evento::first() ?? Evento::create(['titulo' => 'Evento']);

        return Pergunta::create(['evento_id' => $evento->id, 'texto' => $texto]);
    }

    public function test_tabela_pivot_existe_com_colunas_e_indice_unico(): void
    {
        $this->assertTrue(Schema::hasTable('pergunta_user'));
        $this->assertTrue(Schema::hasColumns('pergunta_user', ['pergunta_id', 'user_id', 'created_at', 'updated_at']));

        $user = User::factory()->create();
        $pergunta = $this->pergunta();

        $this->expectException(\Illuminate\Database\QueryException::class);
        \DB::table('pergunta_user')->insert(['pergunta_id' => $pergunta->id, 'user_id' => $user->id]);
        \DB::table('pergunta_user')->insert(['pergunta_id' => $pergunta->id, 'user_id' => $user->id]);
    }

    public function test_relacionamentos_sao_belongs_to_many(): void
    {
        $this->assertInstanceOf(BelongsToMany::class, (new Pergunta)->votos());
        $this->assertInstanceOf(BelongsToMany::class, (new User)->perguntasVotadas());
    }

    public function test_anonimo_nao_pode_votar(): void
    {
        $pergunta = $this->pergunta();

        $this->post(route('perguntas.votar', $pergunta))->assertRedirect(route('login'));
        $this->assertDatabaseCount('pergunta_user', 0);
    }

    public function test_votar_insere_e_votar_de_novo_remove(): void
    {
        $user = User::factory()->create();
        $pergunta = $this->pergunta();

        $this->actingAs($user)->post(route('perguntas.votar', $pergunta))->assertRedirect();
        $this->assertDatabaseHas('pergunta_user', ['pergunta_id' => $pergunta->id, 'user_id' => $user->id]);

        $this->actingAs($user)->post(route('perguntas.votar', $pergunta))->assertRedirect();
        $this->assertDatabaseMissing('pergunta_user', ['pergunta_id' => $pergunta->id, 'user_id' => $user->id]);
    }

    public function test_perguntas_ordenadas_por_votos_decrescente(): void
    {
        $pouco = $this->pergunta('Pergunta com 1 voto');
        $nenhum = $this->pergunta('Pergunta sem votos');
        $muito = $this->pergunta('Pergunta com 3 votos');

        $usuarios = User::factory()->count(3)->create();
        $pouco->votos()->attach($usuarios[0]->id);
        $muito->votos()->attach($usuarios->pluck('id'));

        $response = $this->get(route('eventos.show', $muito->evento_id))->assertOk();

        $ids = $response->viewData('perguntas')->pluck('id')->all();
        $this->assertSame([$muito->id, $pouco->id, $nenhum->id], $ids);
        $this->assertSame(3, $response->viewData('perguntas')->first()->votos_count);
    }
}
