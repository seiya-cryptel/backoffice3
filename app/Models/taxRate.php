<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class taxRate extends Model
{
    /**
     * Attributes
     */
    protected $fillable = [
        'tax_date',
        'tax_type',
        'tax_rate1',
        'notes',
        'isvalid',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tax_date' => 'date',
        'tax_type' => 'integer',
        'tax_rate1' => 'decimal:4',
        'isvalid' => 'boolean',
    ];

    /**
     * accessors and mutators
     */
    public function taxDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : date('Y-m-d', strtotime($value)),
            set: fn ($value) => $this->attributes['tax_date'] = $value === '' ? null : $value, 
        );
    }

    /**
     * Get the tax rate
     * param $strDate    対象日付 yyyy-mm-dd
     * return array     税率
     */
    static public function getRate($strDate)
    {
        $rates = [];
        $TaxRate = self::query()
            ->where('tax_date', '<=', $strDate)
            ->orderBy('tax_date', 'desc')
            ->first();
        return ['0' => 0, '1' => $TaxRate->tax_rate1, '2' => $TaxRate->tax_rate2];
    }
}
