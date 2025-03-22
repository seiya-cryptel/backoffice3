<?php

namespace App\Livewire;

use Livewire\Component;

use App\Traits\Utilities;

use App\Models\Estimate;
use App\Models\estimateDetail;
use App\Models\Client;
use App\Models\Service;
use App\Models\PersonRole;

abstract class EstimateEditBase extends Component
{
    use Utilities;

    /**
     * Client
     */
    public $clients;
    /**
     * Service
     */
    public $services;
    /**
     * person roles
     */
    public $personRoles;

    /**
     * fields
     */
    public $client_id, 
        $estimate_no, $estimate_title, $estimate_date, 
        $deliverly_date, $deliverly_place, $payment_notice, $valid_until, $show_ceo, 
        $notes, $isvalid;

    /**
     * id valud
     */
    public $id;

    /**
     * Estimate details
     */
    public $estimateDetails = [];
    /**
     * new Estimate detail
     */
    public $newEstimateDetail = [];

    /**
     * validation rules
     */
    protected $rules = [
        'estimate_no' => 'required',
        'client_id' => 'required',
        'estimate_title' => 'required',
    ];

    /**
     * reset estimate detail fields
     */
    protected function resetNewEstimateDetailFields()
    {
        $this->newEstimateDetail = [
            'estm_dtl_order' => '',
            'service_id' => '',
            'person_role_id' => '1',
            'estm_dtl_title' => '',
            'estm_dtl_unit_price' => '',
            'estm_dtl_quantity' => '',
            'estm_dtl_unit' => '',
            'estm_dtl_tax_type' => '1',
            'estm_dtl_tax' => '0',
            'estm_dtl_amount' => '0',
            'estm_dtl_acc_item' => '売上',
            'notes' => '',
        ];
    }

    /**
     * calculate estimate detail tax and amount
     */
    protected function calculateEstimateDetail($key)
    {
        $unitPrice = $this->UtlStr2Number($this->estimateDetails[$key]['estm_dtl_unit_price']);
        $quantity = $this->UtlStr2Number($this->estimateDetails[$key]['estm_dtl_quantity']);
        $taxType = $this->estimateDetails[$key]['estm_dtl_tax_type'];
        $taxRate = $taxType == 0 ? 0 : ($taxType == 1 ? 0.1 : 0.8);
        $taxValue = floor($unitPrice * $quantity * $taxRate);
        if(!is_null($unitPrice) && !is_null($quantity)) {
            $this->estimateDetails[$key]['estm_dtl_tax'] = $this->UtlNumber2Str($taxValue, config('app.decimal_digits'));
            $this->estimateDetails[$key]['estm_dtl_amount'] = $this->UtlNumber2Str(($unitPrice * $quantity + $taxValue), config('app.decimal_digits'));
        }
    }

    /**
     * calculate new estimate detail tax and amount
     */
    protected function calculateNewEstimateDetail()
    {
        $unitPrice = $this->UtlStr2Number($this->newEstimateDetail['estm_dtl_unit_price']);
        $quantity = $this->UtlStr2Number($this->newEstimateDetail['estm_dtl_quantity']);
        $taxType = $this->newEstimateDetail['estm_dtl_tax_type'];
        $taxRate = $taxType == 0 ? 0 : ($taxType == 1 ? 0.1 : 0.8);
        $taxValue = floor($unitPrice * $quantity * $taxRate);
        if(!is_null($unitPrice) && !is_null($quantity)) {
            $this->newEstimateDetail['estm_dtl_tax'] = $this->UtlNumber2Str($taxValue, config('app.decimal_digits'));
            $this->newEstimateDetail['estm_dtl_amount'] = $this->UtlNumber2Str(($unitPrice * $quantity + $taxValue), config('app.decimal_digits'));
        }
    }

    /**
     * check if the estimate detail is empty
     */
    protected function isEstimateDetailEmpty()
    {
        return (
            !empty($this->newEstimateDetail['estm_dtl_order']) ||
            !empty($this->newEstimateDetail['estm_dtl_title']) ||
            !empty($this->newEstimateDetail['estm_dtl_unit_price']) ||
            !empty($this->newEstimateDetail['estm_dtl_quantity']) ||
            !empty($this->newEstimateDetail['estm_dtl_unit']) ||
            !empty($this->newEstimateDetail['notes'])
            ) ? false : true;
    }

    /**
     * saev Estimate details
     */
    protected function saveEstimateDetails() {
        // 新規見積明細に入力が残っている場合はエラー表示
        if(!$this->isEstimateDetailEmpty()) {
            throw new \Exception('新規明細が追加されていません。');
        }
        // estimateDetails配列にない明細を削除
        $estimateDetails = estimateDetail::where('estimate_id', $this->id)->get();
        foreach($estimateDetails as $estimateDetail) {
            // 明細レコードのidが$this->estimateDetailsにない場合は削除
            if(!in_array($estimateDetail->id, array_column($this->estimateDetails, 'id'))) {
                $estimateDetail->delete();
            }
        }
        // estimateDetails配列の明細を保存
        foreach($this->estimateDetails as $estimateDetail) {
            if(isset($estimateDetail['id'])) {
                estimateDetail::find($estimateDetail['id'])->update($estimateDetail);
            } else {
                estimateDetail::create(array_merge($estimateDetail, ['estimate_id' => $this->id]));
            }
        }
    }

    /**
     * mount function
     */
    public function mount($id = null)
    {
        $this->id = $id;
        $this->clients = Client::all();
        $this->services = Service::all();
        $this->personRoles = PersonRole::all();
        $this->resetNewEstimateDetailFields();
    }

    /**
     * リスナー
     */
    protected $listeners = [
        'deleteEstimateDetailListener' => 'deleteEstimateDetail',
        'addEstimateDetailListener' => 'addEstimateDetail',
        'resetEstimateDetailListener' => 'resetEstimateDetail',
    ];

    abstract public function render();

    /**
     * Cancel add/edit form and redirect to the master list
     * @return void
     */
    public function cancelEstimate() {
        return redirect()->route('estimates');
    }

    /**
     * add estimate detail
     */
    public function addEstimateDetail()
    {
        $this->validate([
            'newEstimateDetail.estm_dtl_order' => 'required|numeric',
            'newEstimateDetail.estm_dtl_title' => 'required',
        ]);

        $this->estimateDetails[] = [
            'estimate_id' => $this->id,
            'estm_dtl_order' => $this->newEstimateDetail['estm_dtl_order'],
            'service_id' => $this->newEstimateDetail['service_id'],
            'person_role_id' => $this->newEstimateDetail['person_role_id'],
            'estm_dtl_title' => $this->newEstimateDetail['estm_dtl_title'],
            'estm_dtl_unit_price' => $this->newEstimateDetail['estm_dtl_unit_price'],
            'estm_dtl_quantity' => $this->newEstimateDetail['estm_dtl_quantity'],
            'estm_dtl_unit' => $this->newEstimateDetail['estm_dtl_unit'],
            'estm_dtl_tax_type' => $this->newEstimateDetail['estm_dtl_tax_type'],
            'estm_dtl_tax' => $this->newEstimateDetail['estm_dtl_tax'],
            'estm_dtl_amount' => $this->newEstimateDetail['estm_dtl_amount'],
            'estm_dtl_acc_item' => $this->newEstimateDetail['estm_dtl_acc_item'],
            'notes' => $this->newEstimateDetail['notes'],
        ];

        $this->resetNewEstimateDetailFields();
    }

    /**
     * reset estimate detail
     */
    public function resetEstimateDetail()
    {
        $this->resetNewEstimateDetailFields();
    }

    /**
     * 契約明細の削除
     */
    public function deleteEstimateDetail($key) {
        unset($this->estimateDetails[$key]);
    }

    /**
     * 見積明細の表示順が変更された
     */
    public function updateEstimateDetailOrder($key, $value)
    {
        $this->validate([
            'estimateDetails.*.estm_dtl_order' => 'required|numeric',
        ]);
    }
    /**
     * 見積明細の見積単価が変更された
     */
    public function updateEstimateDetailUnitPrice($key, $value)
    {
        $value = $this->UtlStr2Number($value);
        $this->estimateDetails[$key]['estm_dtl_unit_price'] = $this->UtlNumber2str($value, config('app.decimal_digits'));
        $this->calculateEstimateDetail($key);
    }
    /**
     * 見積明細の数量が変更された
     */
    public function updateEstimateDetailQuantity($key, $value)
    {
        $value = $this->UtlStr2Number($value);
        $this->estimateDetails[$key]['estm_dtl_quantity'] = $this->UtlNumber2str($value, config('app.decimal_digits'));
        $this->calculateEstimateDetail($key);
    }
    /**
     * 新規見積明細の税区分が変更された
     */
    public function updateEstimateDetailTaxType($key, $value)
    {
        $this->calculateEstimateDetail($key);
    }

    /**
     * 新規見積明細の単価が変更された
     */
    public function updateNewEstimateDetailUnitPrice($value)
    {
        $value = $this->UtlStr2Number($value);
        $this->newEstimateDetail['estm_dtl_unit_price'] = $this->UtlNumber2Str($value, config('app.decimal_digits'));
        $this->calculateNewEstimateDetail();
    }
    /**
     * 新規見積明細の数量が変更された
     */
    public function updateNewEstimateDetailQuantity($value)
    {
        $value = $this->UtlStr2Number($value);
        $this->newEstimateDetail['estm_dtl_quantity'] = $this->UtlNumber2Str($value, config('app.decimal_digits'));
        $this->calculateNewEstimateDetail();
    }
    /**
     * 新規見積明細の税区分が変更された
     */
    public function updateNewEstimateDetailTaxType($value)
    {
        $this->calculateNewEstimateDetail();
    }
}
