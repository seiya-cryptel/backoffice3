<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

use App\Models\Bill;
use App\Models\BillDetail;

class estimate extends Model
{
    /**
     * Relationships
     */
    public function client()
    {
        return $this->belongsTo('App\Models\client', 'client_id', 'id');
    }
    public function estimateDetails()
    {
        return $this->hasMany('App\Models\estimateDetail', 'estimate_id', 'id');
    }
    
    /**
     * Attributes
     */
    protected $fillable = [
        'estimate_no',
        'client_id',
        'estimate_title',
        'estimate_date',
        'delivery_date',
        'delivery_place',
        'payment_notice',
        'valid_until',
        'show_ceo',
        'notes',
        'isvalid',
    ];
    /**
     * validation rules
     */
    public static $rules = [
        'client_id' => 'required',
        'estimate_no' => 'required',
        'estimate_title' => 'required',
        'isvalid' => 'required',
    ];
    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'client_id' => 'integer',
        // 'estimate_date' => 'date',   MySQL DATE型には不要
        'show_ceo' => 'boolean',
    ];

    /**
     * 明細の税込額の合計を取得
     */
    public function getAmount()
    {
        $amount = 0;
        foreach($this->estimateDetails as $detail) {
            $amount += $detail->estm_dtl_amount;
        }
        return $amount;
    }

    /**
     * 見積を明細を含めて複製する
     * @return estimate
     */
    public function duplicate()
    {
        $Estimate = $this->replicate();
        $Estimate->estimate_no = Estimate::getNextEstimateNo();
        $Estimate->save();
        foreach($this->estimateDetails as $detail) {
            $EstimateDetail = $detail->replicate();
            $EstimateDetail->estimate_id = $Estimate->id;
            $EstimateDetail->save();
        }
        return $Estimate;
    }

    /**
     * 見積から見積を作成する
     */
    public function createBill()
    {
        $Bill = Bill::create([
            'bill_no' => Bill::getNextBillNo(),
            'client_id' => $this->client_id,
            'person_role_id' => 1,
            'bill_title' => $this->estimate_title,
            'bill_date' => date('Y-m-d'),
            'payment_notice' => $this->payment_notice,
            'show_ceo' => $this->show_ceo,
            'notes' => $this->notes,
            // 'isvalid' => $this->isvalid,
            ]);
            
        foreach($this->estimateDetails as $estimateDetail) {
            if($estimateDetail->person_role_id != 1) {
                continue;
            }

            BillDetail::create([
                'bill_id' => $Bill->id,
                'bill_dtl_order' => $estimateDetail->estm_dtl_order,
                'service_id' => $estimateDetail->service_id,
                'person_role_id' => 1,
                'bill_dtl_title' => $estimateDetail->estm_dtl_title,
                'bill_dtl_unit_price' => $estimateDetail->estm_dtl_unit_price,
                'bill_dtl_quantity' => $estimateDetail->estm_dtl_quantity,
                'bill_dtl_unit' => $estimateDetail->estm_dtl_unit,
                'bill_dtl_tax_rate' => $estimateDetail->estm_dtl_tax_type,
                'bill_dtl_tax' => $estimateDetail->estm_dtl_tax,
                'bill_dtl_amount' => $estimateDetail->estm_dtl_amount,
                'bill_dtl_acc_item' => $estimateDetail->estm_dtl_acc_item,
            ]);
        }
        return $Bill;
    }

    /**
     * accessors and mutators
     */
    public function estimateDate() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? '' : date('Y-m-d', strtotime($value)),
            set: fn ($value) => $this->attributes['estimate_date'] = $value === '' ? null : $value,
        );
    }
    public function showCeo() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? false : $value,
            set: fn ($value) => $this->attributes['show_ceo'] = $value === '' ? false : $value,
        );
    }

    /**
     * 見積番号の自動採番
     * @param string $yyyymm   対象年月
     * @return string   新しい見積番号
     */
    public static function getNextEstimateNo($yyyymm = null)
    {
        if (empty($yyyymm)) {
            $yyyymm = date('Ym');
        }
        $maxEstimateNo = self::where('estimate_no', 'like', $yyyymm . '%')
            ->max('estimate_no');
        if (empty($maxEstimateNo)) {
            return $yyyymm . '-001';
        }
        return sprintf('%s-%03d', $yyyymm, intval(substr($maxEstimateNo, 7)) + 1);
    }

}
