<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Garde extends Model
{
    // pharmacie_id est fixé par la relation, jamais par un formulaire.
    protected $fillable = ['debut', 'fin'];

    protected function casts(): array
    {
        return [
            'debut' => 'date',
            'fin' => 'date',
        ];
    }

    public function pharmacie(): BelongsTo
    {
        return $this->belongsTo(Pharmacie::class);
    }
}
