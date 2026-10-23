<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pharmacie extends Model
{
    use HasFactory;

    protected $table = 'pharmacies';

    // user_id n'est volontairement pas ici : on le fixe nous-mêmes.
    protected $fillable = [
        'nom', 'adresse', 'quartier', 'telephone',
        'latitude', 'longitude', 'validee', 'de_garde',
    ];

    protected function casts(): array
    {
        return [
            'validee' => 'boolean',
            'de_garde' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function gardes(): HasMany
    {
        return $this->hasMany(Garde::class);
    }

    public function medicaments(): BelongsToMany
    {
        return $this->belongsToMany(Medicament::class, 'stocks')
            ->withPivot('disponible', 'quantite')
            ->withTimestamps();
    }

    // Règle unique : une pharmacie est de garde si une garde couvre la date du jour.
    public function scopeDeGarde(Builder $query): Builder
    {
        $aujourdhui = today()->toDateString();

        return $query->whereHas('gardes', fn ($g) => $g
            ->whereDate('debut', '<=', $aujourdhui)
            ->whereDate('fin', '>=', $aujourdhui));
    }

    public function estDeGarde(): bool
    {
        $aujourdhui = today()->toDateString();

        return $this->gardes()
            ->whereDate('debut', '<=', $aujourdhui)
            ->whereDate('fin', '>=', $aujourdhui)
            ->exists();
    }
}
