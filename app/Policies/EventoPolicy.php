<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;

class EventoPolicy
{
    /**
     * Apenas o dono do evento pode moderar suas perguntas.
     */
    public function moderate(User $user, Evento $evento): bool
    {
        return $user->id === $evento->user_id;
    }
}
