@extends('layouts.app')

@section('title', 'Moderação — ' . $evento->titulo)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold m-0">🛡️ Fila de Moderação — {{ $evento->titulo }}</h4>
    <a href="{{ route('eventos.show', $evento->id) }}" class="btn btn-sm btn-outline-secondary">← Voltar ao evento</a>
</div>

@forelse($moderacoes as $moderacao)
    <div class="mb-3 p-4 rounded bg-gray-800 shadow-md border-l-4 border-blue-500">
        <p class="text-white mb-1"><strong>Pergunta:</strong> {{ $moderacao->pergunta->texto }}</p>
        <p class="text-secondary mb-2"><strong>Motivo:</strong> {{ $moderacao->motivo }}</p>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary">{{ $moderacao->status }}</span>
            <form action="{{ route('moderacoes.status', $moderacao) }}" method="POST" class="m-0">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="aprovada">
                <button class="btn btn-sm btn-success">Aprovar</button>
            </form>
            <form action="{{ route('moderacoes.status', $moderacao) }}" method="POST" class="m-0">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="rejeitada">
                <button class="btn btn-sm btn-danger">Rejeitar</button>
            </form>
        </div>
    </div>
@empty
    <div class="alert alert-dark text-center p-4">Nenhuma denúncia na fila.</div>
@endforelse

<div class="d-flex justify-content-center mt-4">{{ $moderacoes->links() }}</div>
@endsection
