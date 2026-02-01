<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'name',
        'sex',
        'breed_id',
        'breed_text',
        'breed_unknown',
        'dob',
        'approx_age_years',
        'is_dangerous',
    ];

    protected $casts = [
        'breed_unknown' => 'boolean',
        'dob' => 'date',
        'is_dangerous' => 'boolean',
    ];

    /**
     * Get the type (e.g. Dog, Cat) this pet belongs to.
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Get the breed this pet belongs to, if known.
     */
    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }

    /**
     * Age in years: computed from dob if set, otherwise approx_age_years.
     */
    public function getAgeYearsAttribute(): ?int
    {
        if ($this->dob) {
            return (int) Carbon::parse($this->dob)->diffInYears(Carbon::now());
        }

        return $this->approx_age_years;
    }

    /**
     * Breed display: breed name, "Mixed: …", or "Unknown".
     */
    public function getBreedDisplayAttribute(): string
    {
        if ($this->breed_id && $this->breed) {
            return $this->breed->name;
        }

        if ($this->breed_text) {
            return 'Mixed: ' . $this->breed_text;
        }

        return 'Unknown';
    }
}
