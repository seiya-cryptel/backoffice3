<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class contract extends Model
{
    /**
     * Relationships
     */
    public function client()
    {
        return $this->belongsTo('App\Models\client', 'client_id', 'id');
    }
    public function contractDetails()
    {
        return $this->hasMany('App\Models\contractDetail', 'contract_id', 'id');
    }

    /**
     * Attributes
     */
    protected $fillable = [
        'client_id',
        'contract_order',
        'contract_title',
        'contract_start',
        'contract_end',
        'contract_service_in',
        'contract_first_bill',
        'contract_end_bill',
        'contract_interval',
        'contract_month_ofs',
        'contract_bill_month',
        'contract_bill_day',
        'contract_next_date',
        'notes',
        'isvalid',
    ];

    /**
     * validation rules
     */
    public static $rules = [
        'client_id' => 'required',
        'contract_order' => 'required',
        'contract_title' => 'required',
        'isvalid' => 'required',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'client_id' => 'integer',
        'contract_order' => 'integer',
        'contract_start' => 'date',
        'contract_end' => 'date',
        'contract_service_in' => 'date',
        'contract_first_bill' => 'date',
        'contract_end_bill' => 'date',
        'contract_interval' => 'integer',
        'contract_month_ofs' => 'integer',
        'contract_bill_month' => 'integer',
        'contract_bill_day' => 'integer',
        'contract_next_date' => 'date',
        'isvalid' => 'boolean',
    ];

    /**
     * accessors and mutators
     */
    /*
    public function contractStart($value) : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : date('Y-m-d', strtotime($value)),
            set: fn ($value) => $this->attributes['contract_start'] = $value === '' ? null : $value,
        );
    }
    */
    public function getContractStartAttribute($value)
    {
        return $value === null ? '' : date('Y-m-d', strtotime($value));
    }
    public function setContractStartAttribute($value)
    {
        $this->attributes['contract_start'] = $value === '' ? null : $value;
    }
    public function getContractEndAttribute($value)
    {
        return $value === null ? '' : date('Y-m-d', strtotime($value));
    }
    public function setContractEndAttribute($value)
    {
        $this->attributes['contract_end'] = $value === '' ? null : $value;
    }
    public function getContractServiceInAttribute($value)
    {
        return $value === null ? '' : date('Y-m-d', strtotime($value));
    }
    public function setContractServiceInAttribute($value)
    {
        $this->attributes['contract_service_in'] = $value === '' ? null : $value;
    }
    public function getContractFirstBillAttribute($value)
    {
        return $value === null ? '' : date('Y-m-d', strtotime($value));
    }
    public function setContractFirstBillAttribute($value)
    {
        $this->attributes['contract_first_bill'] = $value === '' ? null : $value;
    }
    public function getContractEndBillAttribute($value)
    {
        return $value === null ? '' : date('Y-m-d', strtotime($value));
    }
    public function setContractEndBillAttribute($value)
    {
        $this->attributes['contract_end_bill'] = $value === '' ? null : $value;
    }
    public function getContractNextDateAttribute($value)
    {
        return $value === null ? '' : date('Y-m-d', strtotime($value));
    }
    public function setContractNextDateAttribute($value)
    {
        $this->attributes['contract_next_date'] = $value === '' ? null : $value;
    }

    /**
     * 請求書発行対象の契約を取得
     * @param string $target_date
     * @return array
     * 
     * 条件: 有効、次回請求日 < $target_date、請求間隔 > 0
     *      isvalid, contract_next_date < $target_date, contract_interval > 0
     */
    public static function getBillgenContracts($target_date)
    {
        return self::query()->with('contractDetails')
            ->where('isvalid', true)
            ->where(function($query) use ($target_date) {
                $query->whereNull('contract_end_bill')
                      ->orWhere('contract_next_date', '<=', 'contract_end_bill');
            })
            ->where('contract_next_date', '<', $target_date)
            ->where('contract_interval', '>', 0)
            ->get();
    }

    /**
     * 次回請求日を更新する
     */
    public function updateNextBillDate()
    {
        // 請求間隔が 1 未満の場合は更新しない
        if($this->contract_interval < 1) return;

        $dtNextDate = strtotime($this->contract_next_date); // 次回請求日（更新前）
        $strNextDateDisp = date('Y-m-d', $dtNextDate);
        if($this->contract_bill_day == 99)
        {   // 末日
            // 請求月の1日に（interval + 1）月を足して、1日前を求める
            $dtTempDate = strtotime(date('Y-m-1', $dtNextDate));    // 作成年月の 1 日
            $dtTempDate = strtotime('+' . ($this->contract_interval + 1) . ' month', $dtTempDate);
            $dtTempDate = strtotime('-1 day', $dtTempDate);
            $this->contract_next_date = date('Y-m-d', $dtTempDate);
        }
        else
        {
            $this->contract_next_date = date('Y-m-d', strtotime('+' . $this->contract_interval . ' month', $dtNextDate));
        }
        $this->save();
    }
}
