<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pharmacie extends Model
{
    use HasFactory;

    protected $table = 'pharmacies';

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

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function medicaments(): BelongsToMany
    {
        return $this->belongsToMany(Medicament::class, 'stocks')
            ->withPivot('disponible', 'quantite')
            ->withTimestamps();
    }
}
