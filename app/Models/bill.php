<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for the bill table
 */
class bill extends Model
{
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
     */
    public function getAmount()
    {
        $amount = 0;
        foreach ($this->billDetails as $billDetail) {
            $amount += $billDetail->bill_dtl_amount;
        }
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
