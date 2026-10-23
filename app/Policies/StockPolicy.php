<?php

namespace App\Policies;

use App\Models\Stock;
use App\Models\User;

class StockPolicy
{
    // Seul le pharmacien propriétaire de la pharmacie peut modifier ce stock.
    public function update(User $user, Stock $stock): bool
    {
        return $user->role === 'pharmacien'
            && $stock->pharmacie()->where('user_id', $user->id)->exists();
    }
}
