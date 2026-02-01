<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the breeds of this type.
     */
    public function breeds(): HasMany
    {
        return $this->hasMany(Breed::class);
    }

    /**
     * Get the pets of this type.
     */
    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }
}
