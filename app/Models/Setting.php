<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Setting
 *
 * @property int $id
 * @property int $retirement_age
 * @property float $income_tax_rate
 * @property float $retirement_contribution_rate
 * @property float $longevity_bonus
 * @property float $leave_pay
 * @property int $minimum_seniority_age
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'retirement_age',
        'income_tax_rate',
        'retirement_contribution_rate',
        'longevity_bonus',
        'minimum_seniority_age',
    ];

    protected $casts = [
        'income_tax_rate' => 'decimal:3',
        'retirement_contribution_rate' => 'decimal:3',
        'longevity_bonus' => 'decimal:3',
    ];
}