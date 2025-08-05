<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Utilities;

class contractDetail extends Model
{
    use Utilities;

    /**
     * Relationships
     */
    public function contract()
    {
        return $this->belongsTo('App\Models\Contract', 'contract_id', 'id');
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
        'contract_id',
        'cont_dtl_order',
        'service_id',
        'person_role_id',
        'cont_dtl_title',
        'cont_dtl_unit_price',
        'cont_dtl_quantity',
        'cont_dtl_unit',
        'cont_dtl_tax_type',
        'cont_dtl_acc_item',
        'notes',
        'isvalid',
    ];

    /**
     * validation rules
     */
    public static $rules = [
        'contract_id' => 'required|integer',
        'cont_dtl_order' => 'required|integer',
        'client_person_role_id' => 'required|integer',
        'cont_dtl_unit_price' => 'required|numeric',
        'cont_dtl_quantity' => 'required|numeric',
        'cont_dtl_tax_type' => 'required|integer',
        'notes' => 'nullable|string|max:255',
        'isvalid' => 'required|boolean',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'contract_id' => 'integer',
        'cont_dtl_order' => 'integer',
        'service_id' => 'integer',
        'client_role_id' => 'integer',
        'cont_dtl_unit_price' => 'decimal:4',
        'cont_dtl_quantity' => 'decimal:4',
        'cont_dtl_tax_type' => 'integer',
        'isvalid' => 'boolean',
    ];

    /**
     * accessors and mutators
     */
    public function contDtlOrder(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : $value,
            set: fn ($value) => $this->attributes['cont_dtl_order'] = $value === '' ? null : $value, 
        );
    }
    public function contDtlUnitPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : number_format($value),
            set: fn ($value) => $this->attributes['cont_dtl_unit_price'] = $value === '' ? 0 : $this->UtlStr2Number($value), 
        );
    }
    public function contDtlQuantity(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : number_format($value),
            set: fn ($value) => $this->attributes['cont_dtl_quantity'] = $value === '' ? 0 : $this->UtlStr2Number($value), 
        );
    }

    /**
     * 請求書発行対象の契約明細を取得
     * @param int $contract_id
     * @return array
     * 
     * 条件: 有効、contract_id指定、役割IDが1
     *      isvalid, contract_id < $contract_id, person_role_id = 1
     */
    static public function getBillgenContractDetails($contract_id)
    {
        return self::query()
            ->where('isvalid', true)
            ->where('contract_id', $contract_id)
            ->where('person_role_id', 1)
            ->orderBy('cont_dtl_order', 'asc')
            ->get();
    }

    /**
     * accessor/mutator
     */
    public function serviceId(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : $value,
            set: fn ($value) => $value === '' ? null : $value,
        );
    }
}
