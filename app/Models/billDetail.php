<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Utilities;

class billDetail extends Model
{
    use Utilities;
    
    /**
     * Relationships
     */
    public function bill()
    {
        return $this->belongsTo('App\Models\bill', 'bill_id', 'id');
    }
    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id', 'id');
    }
    public function personRole()
    {
        return $this->belongsTo('App\Models\PersonRole', 'person_role_id', 'id');
    }

    /**
     * Attributes
     */
    protected $fillable = [
        'bill_id',
        'bill_dtl_order',
        'service_id',
        'person_role_id',
        'bill_dtl_title',
        'bill_dtl_unit_price',
        'bill_dtl_quantity',
        'bill_dtl_unit',
        'bill_dtl_tax_type',
        'bill_dtl_tax',
        'bill_dtl_amount',
        'bill_dtl_acc_item',
        'notes',
        'isvalid',
    ];

    /**
     * validation rules
     */
    public static $rules = [
        'bill_id' => 'required|integer',
        'bill_dtl_order' => 'required|integer',
        'service_id' => 'integer',
        'person_role_id' => 'required|integer',
        'bill_dtl_unit_price' => 'required|numeric',
        'bill_dtl_quantity' => 'required|numeric',
        'bill_dtl_tax_type' => 'required|integer',
        'bill_dtl_tax' => 'required|numeric',
        'bill_dtl_amount' => 'required|numeric',
        'notes' => 'nullable|string|max:255',
        'isvalid' => 'required|boolean',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'bill_id' => 'integer',
        'bill_dtl_order' => 'integer',
        'service_id' => 'integer',
        'person_role_id' => 'integer',
        'bill_dtl_unit_price' => 'float',
        'bill_dtl_quantity' => 'float',
        'bill_dtl_tax_type' => 'integer',
        'bill_dtl_tax' => 'float',
        'bill_dtl_amount' => 'float',
        'isvalid' => 'boolean',
    ];

    /**
     * accessor and mutator
     */
    public function ebillDtlOrder() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : $value,
            set: fn ($value) => $this->attributes['bill_dtl_order'] = $value === '' ? null : $this->UtlStr2Number($value), 
        );
    }
    public function serviceId() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : $value,
            set: fn ($value) => $this->attributes['service_id'] = $value === '' ? null : $value, 
        );
    }
    public function billDtlUnitPrice() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : number_format($value),
            set: fn ($value) => $this->attributes['bill_dtl_unit_price'] = $value === '' ? null : $this->UtlStr2Number($value), 
        );
    }
    public function billDtlQuantity() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : number_format($value, 2),
            set: fn ($value) => $this->attributes['bill_dtl_quantity'] = $value === '' ? null : $this->UtlStr2Number($value), 
        );
    }
    public function billDtlTax() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : number_format($value),
            set: fn ($value) => $this->attributes['bill_dtl_tax'] = $value === '' ? null : $this->UtlStr2Number($value), 
        );
    }
    public function billDtlAmount() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : $value,   // because sum() applied to this column
            set: fn ($value) => $this->attributes['bill_dtl_amount'] = $value === '' ? null : $this->UtlStr2Number($value), 
        );
    }
}
