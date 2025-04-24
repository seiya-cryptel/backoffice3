<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Utilities;

/**
 * Model for the bill table
 */
class bill extends Model
{
    use Utilities;

    /**
     * Relationships
     */
    public function client()
    {
        return $this->belongsTo('App\Models\client', 'client_id', 'id');
    }
    public function person_role()
    {
        return $this->belongsTo('App\Models\personRole', 'person_role_id', 'id');
    }
    public function billDetails()
    {
        return $this->hasMany('App\Models\billDetail', 'bill_id', 'id');
    }
    
    /**
     * Attributes
     */
    protected $fillable = [
        'bill_no',
        'client_id',
        'person_role_id',
        'bill_title',
        'bill_date',
        'payment_notice',
        'show_ceo',
        'notes',
        'isvalid',
    ];
    /**
     * validation rules
     */
    public static $rules = [
        'bill_no' => 'required',
        'client_id' => 'required',
        'bill_title' => 'required',
        'isvalid' => 'required',
    ];
    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'client_id' => 'integer',
        // 'bill_date' => 'date',   MySQL DATE型には不要
        'show_ceo' => 'boolean',
    ];

    /**
     * Get the total amount of the tax-inclusive amount of the details
     * @param 税率配列
     */
    public function getAmount($taxRates)
    {
        $amounts = [0, 0, 0];   // 金額 税率0, 1, 2に分けて合計
        $taxes = [0, 0, 0];     // 税額 税率 0, 1, 2 に分けて金額の合計から計算する
        foreach ($this->billDetails as $billDetail) {
            $taxType = $billDetail->bill_dtl_tax_type;
            $amounts[$taxType] += round(
                $this->UtlStr2Number($billDetail->bill_dtl_unit_price) 
                * $this->UtlStr2Number($billDetail->bill_dtl_quantity)
            );
        }
        // $taxes[0] = round($amounts[0] * $taxRates[0]); 非課税
        $taxes[1] = floor($amounts[1] * $taxRates[1]);
        $taxes[2] = floor($amounts[2] * $taxRates[2]);
        $amount = array_sum($amounts) + array_sum($taxes);
        return $amount;
    }

    /**
     * 請求番号の自動採番
     * @param string $yyyymm   対象年月
     * @return string   新しい請求番号
     */
    public static function getNextBillNo($yyyymm = null)
    {
        if (empty($yyyymm)) {
            $yyyymm = date('Ym');
        }
        $maxBillNo = self::where('bill_no', 'like', $yyyymm . '%')
            ->max('bill_no');
        if (empty($maxBillNo)) {
            return $yyyymm . '-001';
        }
        return sprintf('%s-%03d', $yyyymm, intval(substr($maxBillNo, 7)) + 1);
    }

}
