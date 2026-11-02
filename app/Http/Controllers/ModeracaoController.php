<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModeracaoRequest;
use App\Models\Evento;
use App\Models\Moderacao;
use App\Models\Pergunta;
use Illuminate\Http\Request;

class ModeracaoController extends Controller
{
    /**
     * Registra a denúncia de uma pergunta (qualquer usuário autenticado).
     */
    public function store(StoreModeracaoRequest $request, Pergunta $pergunta)
    {
        $pergunta->moderacoes()->create([
            'motivo' => $request->input('motivo'),
        ]);

        return redirect()->route('eventos.show', $pergunta->evento_id)
            ->with('sucesso', 'Denúncia registrada. Obrigado por ajudar a moderar!');
    }

    /**
     * Fila de Moderação do Evento (apenas o dono), com Eager Loading para evitar N+1.
     */
    public function index(Evento $evento)
    {
        $this->authorize('moderate', $evento);

        $moderacoes = Moderacao::with('pergunta')
            ->whereHas('pergunta', fn ($q) => $q->where('evento_id', $evento->id))
            ->latest()
            ->paginate(15);

        return view('moderacoes.index', compact('evento', 'moderacoes'));
    }

    /**
     * Aprova ou rejeita a denúncia (apenas o dono do evento).
     */
    public function updateStatus(Request $request, Moderacao $moderacao)
    {
        $this->authorize('moderate', $moderacao->pergunta->evento);

        $dados = $request->validate([
            'status' => ['required', 'in:aprovada,rejeitada'],
        ]);

        $moderacao->update($dados);

        return back()->with('sucesso', 'Status da moderação atualizado!');
    }
}
