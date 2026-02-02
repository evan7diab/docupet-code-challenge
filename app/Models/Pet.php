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
            return 'Mixed: '.$this->breed_text;
        }

        return 'Unknown';
    }

    /**
     * Resolve breed text for mixed breeds.
     */
    public static function resolveBreedText(?int $breedId, array $data): ?string
    {
        if ($breedId) {
            return null;
        }

        $clarification = $data['breed_clarification'] ?? null;

        if ($clarification === 'mix') {
            return ! empty($data['breed_text']) ? trim($data['breed_text']) : 'Mixed';
        }

        return null;
    }

    /**
     * Determine if breed is unknown.
     * Breed is unknown when neither breed_id nor breed_text is set.
     */
    public static function isBreedUnknown(?int $breedId, ?string $breedText): bool
    {
        return $breedId === null && $breedText === null;
    }

    /**
     * Resolve is_dangerous flag from breed.
     */
    public static function resolveIsDangerous(?int $breedId): bool
    {
        if (! $breedId) {
            return false;
        }

        $breed = Breed::find($breedId);

        return $breed?->is_dangerous ?? false;
    }
}
