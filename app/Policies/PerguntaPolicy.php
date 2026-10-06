<?php

namespace App\Policies;

use App\Models\Pergunta;
use App\Models\User;

class PerguntaPolicy
{
    /**
     * Pode excluir quem é autor da pergunta ou dono do evento da pergunta.
     */
    public function delete(User $user, Pergunta $pergunta): bool
    {
        return $user->id === $pergunta->user_id
            || $user->id === $pergunta->evento->user_id;
    }
}
