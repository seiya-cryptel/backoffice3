<?php

namespace App\Livewire;

use Livewire\Component;

use App\Traits\Utilities;

use App\Models\Bill;
use App\Models\billDetail;
use App\Models\Client;
use App\Models\Service;
use App\Models\PersonRole;

abstract class BillEditBase extends Component
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
    public $client_id, $person_role_id,
        $bill_no, $bill_title, $bill_date, 
        $payment_notice, $show_ceo, 
        $notes, $isvalid;

    /**
     * id valud
     */
    public $id;

    /**
     * Bill details
     */
    public $billDetails = [];
    /**
     * new Bill detail
     */
    public $newBillDetail = [];

    /**
     * validation rules
     */
    protected $rules = [
        'bill_no' => 'required',
        'client_id' => 'required',
        'bill_title' => 'required',
    ];

    /**
     * reset bill detail fields
     */
    protected function resetNewBillDetailFields()
    {
        $this->newBillDetail = [
            'bill_dtl_order' => '',
            'service_id' => '',
            'person_role_id' => '1',
            'bill_dtl_title' => '',
            'bill_dtl_unit_price' => '',
            'bill_dtl_quantity' => '',
            'bill_dtl_unit' => '',
            'bill_dtl_tax_type' => '1',
            'bill_dtl_tax' => '0',
            'bill_dtl_amount' => '0',
            'bill_dtl_acc_item' => '売上',
            'notes' => '',
        ];
    }

    /**
     * calculate bill detail tax and amount
     */
    protected function calculateBillDetail($key)
    {
        $unitPrice = $this->UtlStr2Number($this->billDetails[$key]['bill_dtl_unit_price']);
        $quantity = $this->UtlStr2Number($this->billDetails[$key]['bill_dtl_quantity']);
        $taxType = $this->billDetails[$key]['bill_dtl_tax_type'];
        $taxRate = $taxType == 0 ? 0 : ($taxType == 1 ? 0.1 : 0.8);
        $taxValue = floor($unitPrice * $quantity * $taxRate);
        if(!is_null($unitPrice) && !is_null($quantity)) {
            $this->billDetails[$key]['bill_dtl_tax'] = $this->UtlNumber2Str($taxValue, config('app.decimal_digits'));
            $this->billDetails[$key]['bill_dtl_amount'] = $this->UtlNumber2Str(($unitPrice * $quantity + $taxValue), config('app.decimal_digits'));
        }
    }

    /**
     * calculate new bill detail tax and amount
     */
    protected function calculateNewBillDetail()
    {
        $unitPrice = $this->UtlStr2Number($this->newBillDetail['bill_dtl_unit_price']);
        $quantity = $this->UtlStr2Number($this->newBillDetail['bill_dtl_quantity']);
        $taxType = $this->newBillDetail['bill_dtl_tax_type'];
        $taxRate = $taxType == 0 ? 0 : ($taxType == 1 ? 0.1 : 0.8);
        $taxValue = floor($unitPrice * $quantity * $taxRate);
        if(!is_null($unitPrice) && !is_null($quantity)) {
            $this->newBillDetail['bill_dtl_tax'] = $this->UtlNumber2Str($taxValue, config('app.decimal_digits'));
            $this->newBillDetail['bill_dtl_amount'] = $this->UtlNumber2Str(($unitPrice * $quantity + $taxValue), config('app.decimal_digits'));
        }
    }

    /**
     * check if the bill detail is empty
     */
    protected function isBillDetailEmpty()
    {
        return (
            !empty($this->newBillDetail['bill_dtl_order']) ||
            !empty($this->newBillDetail['bill_dtl_title']) ||
            !empty($this->newBillDetail['bill_dtl_unit_price']) ||
            !empty($this->newBillDetail['bill_dtl_quantity']) ||
            !empty($this->newBillDetail['bill_dtl_unit']) ||
            !empty($this->newBillDetail['notes'])
            ) ? false : true;
    }

    /**
     * saev Bill details
     */
    protected function saveBillDetails() {
        // 新規請求明細に入力が残っている場合はエラー表示
        if(!$this->isBillDetailEmpty()) {
            throw new \Exception('新規明細が追加されていません。');
        }
        // billDetails配列にない明細を削除
        $billDetails = billDetail::where('bill_id', $this->id)->get();
        foreach($billDetails as $billDetail) {
            // 明細レコードのidが$this->billDetailsにない場合は削除
            if(!in_array($billDetail->id, array_column($this->billDetails, 'id'))) {
                $billDetail->delete();
            }
        }
        // billDetails配列の明細を保存
        foreach($this->billDetails as $billDetail) {
            if(isset($billDetail['id'])) {
                billDetail::find($billDetail['id'])->update($billDetail);
            } else {
                billDetail::create(array_merge($billDetail, ['bill_id' => $this->id]));
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
        $this->resetNewBillDetailFields();
    }

    /**
     * リスナー
     */
    protected $listeners = [
        'deleteBillDetailListener' => 'deleteBillDetail',
    ];

    abstract public function render();

    /**
     * Cancel add/edit form and redirect to the master list
     * @return void
     */
    public function cancelBill() {
        return redirect()->route('bills');
    }

    /**
     * add bill detail
     */
    public function addBillDetail()
    {
        $this->validate([
            'newBillDetail.bill_dtl_order' => 'required|numeric',
            'newBillDetail.bill_dtl_title' => 'required',
        ]);

        $this->billDetails[] = [
            'bill_id' => $this->id,
            'bill_dtl_order' => $this->newBillDetail['bill_dtl_order'],
            'service_id' => $this->newBillDetail['service_id'],
            'person_role_id' => $this->newBillDetail['person_role_id'],
            'bill_dtl_title' => $this->newBillDetail['bill_dtl_title'],
            'bill_dtl_unit_price' => $this->newBillDetail['bill_dtl_unit_price'],
            'bill_dtl_quantity' => $this->newBillDetail['bill_dtl_quantity'],
            'bill_dtl_unit' => $this->newBillDetail['bill_dtl_unit'],
            'bill_dtl_tax_type' => $this->newBillDetail['bill_dtl_tax_type'],
            'bill_dtl_tax' => $this->newBillDetail['bill_dtl_tax'],
            'bill_dtl_amount' => $this->newBillDetail['bill_dtl_amount'],
            'bill_dtl_acc_item' => $this->newBillDetail['bill_dtl_acc_item'],
            'notes' => $this->newBillDetail['notes'],
        ];

        $this->resetNewBillDetailFields();
    }

    /**
     * reset bill detail
     */
    public function resetBillDetail()
    {
        $this->resetNewBillDetailFields();
    }

    /**
     * 契約明細の削除
     */
    public function deleteBillDetail($key) {
        unset($this->billDetails[$key]);
    }

    /**
     * 請求明細の表示順が変更された
     */
    public function updateBillDetailOrder($key, $value)
    {
        $this->validate([
            'billDetails.*.bill_dtl_order' => 'required|numeric',
        ]);
    }
    /**
     * 請求明細の請求単価が変更された
     */
    public function updateBillDetailUnitPrice($key, $value)
    {
        $value = $this->UtlStr2Number($value);
        $this->billDetails[$key]['bill_dtl_unit_price'] = $this->UtlNumber2str($value, config('app.decimal_digits'));
        $this->calculateBillDetail($key);
    }
    /**
     * 請求明細の数量が変更された
     */
    public function updateBillDetailQuantity($key, $value)
    {
        $value = $this->UtlStr2Number($value);
        $this->billDetails[$key]['bill_dtl_quantity'] = $this->UtlNumber2str($value, config('app.decimal_digits'));
        $this->calculateBillDetail($key);
    }
    /**
     * 新規請求明細の税区分が変更された
     */
    public function updateBillDetailTaxType($key, $value)
    {
        $this->calculateBillDetail($key);
    }

    /**
     * 新規請求明細の単価が変更された
     */
    public function updateNewBillDetailUnitPrice($value)
    {
        $value = $this->UtlStr2Number($value);
        $this->newBillDetail['bill_dtl_unit_price'] = $this->UtlNumber2Str($value, config('app.decimal_digits'));
        $this->calculateNewBillDetail();
    }
    /**
     * 新規請求明細の数量が変更された
     */
    public function updateNewBillDetailQuantity($value)
    {
        $value = $this->UtlStr2Number($value);
        $this->newBillDetail['bill_dtl_quantity'] = $this->UtlNumber2Str($value, config('app.decimal_digits'));
        $this->calculateNewBillDetail();
    }
    /**
     * 新規請求明細の税区分が変更された
     */
    public function updateNewBillDetailTaxType($value)
    {
        $this->calculateNewBillDetail();
    }
}
