<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Utilities;

class estimateDetail extends Model
{
    use Utilities;

    /**
     * Relationships
     */
    public function estimate()
    {
        return $this->belongsTo('App\Models\Estimate', 'estimate_id', 'id');
    }
    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id', 'id');
    }
    public function PersonRole()
    {
        return $this->belongsTo('App\Models\PersonRole', 'person_role_id', 'id');
    }

    /**
     * Attributes
     */
    protected $fillable = [
        'estimate_id',
        'estm_dtl_order',
        'service_id',
        'person_role_id',
        'estm_dtl_title',
        'estm_dtl_unit_price',
        'estm_dtl_quantity',
        'estm_dtl_unit',
        'estm_dtl_tax_type',
        'estm_dtl_tax',
        'estm_dtl_amount',
        'estm_dtl_acc_item',
        'notes',
        'isvalid',
    ];

    /**
     * validation rules
     */
    public static $rules = [
        'estimate_id' => 'required|integer',
        'estm_dtl_order' => 'required|integer',
        'service_id' => 'integer',
        'person_role_id' => 'required|integer',
        'estm_dtl_unit_price' => 'required|numeric',
        'estm_dtl_quantity' => 'required|numeric',
        'estm_dtl_tax_type' => 'required|integer',
        'estm_dtl_tax' => 'required|numeric',
        'estm_dtl_amount' => 'required|numeric',
        'notes' => 'nullable|string|max:255',
        'isvalid' => 'required|boolean',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'estimate_id' => 'integer',
        'estm_dtl_order' => 'integer',
        'service_id' => 'integer',
        'person_role_id' => 'integer',
        'estm_dtl_unit_price' => 'decimal:4',
        'estm_dtl_quantity' => 'decimal:4',
        'estm_dtl_tax' => 'decimal:4',
        'estm_dtl_amount' => 'decimal:4',
        'isvalid' => 'boolean',
    ];

    /**
     * accessors and mutators
     */
    public function estmDtlOrder() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : $value,
            set: fn ($value) => $this->attributes['estm_dtl_order'] = $value === '' ? null : $this->UtlStr2Number($value), 
        );
    }
    public function serviceId() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : $value,
            set: fn ($value) => $this->attributes['service_id'] = $value === '' ? null : $value, 
        );
    }
    public function estmDtlUnitPrice() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : number_format($value),
            set: fn ($value) => $this->attributes['estm_dtl_unit_price'] = $value === '' ? null : $this->UtlStr2Number($value), 
        );
    }
    public function estmDtlQuantity() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : number_format($value),
            set: fn ($value) => $this->attributes['estm_dtl_quantity'] = $value === '' ? null : $this->UtlStr2Number($value), 
        );
    }
    public function estmDtlTax() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : number_format($value),
            set: fn ($value) => $this->attributes['estm_dtl_tax'] = $value === '' ? null : $this->UtlStr2Number($value), 
        );
    }
    public function estmDtlAmount() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : $value,   // because sum() applied to this column
            set: fn ($value) => $this->attributes['estm_dtl_amount'] = $value === '' ? null : $this->UtlStr2Number($value), 
        );
    }
}
