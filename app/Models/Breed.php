<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Breed extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type_id', 'is_dangerous'];

    protected $casts = [
        'is_dangerous' => 'boolean',
    ];

    /**
     * Get the type (e.g. Dog, Cat) this breed belongs to.
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Get the pets of this breed.
     */
    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }
}
