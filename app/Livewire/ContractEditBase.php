<?php

namespace App\Livewire;

use Livewire\Component;

use App\Traits\Utilities;

use App\Models\Contract;
use App\Models\ContractDetail;
use App\Models\Client;
use App\Models\Service;
use App\Models\PersonRole;

abstract class ContractEditBase extends Component
{
    use Utilities;

    /**
     * Client
     */
    public $client;
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
    public $contract_order, $contract_title,
        $contract_start, $contract_end, $contract_service_in, $contract_first_bill, $contract_end_bill,
        $contract_interval, $contract_month_ofs, $contract_bill_month, $contract_bill_day, $contract_next_date,
        $notes,$isvalid;

    /**
     * id valud
     */
    public $id;

    /**
     * contract details
     */
    public $details = [];
    /**
     * new contract detail
     */
    public $newDetail = [];

    /**
     * validation rules
     */
    protected $rules = [
        'contract_order' => 'required',
        'contract_title' => 'required',
        'isvalid' => 'required',
    ];

    /**
     * reset contract detail fields
     */
    protected function resetNewDetailFields()
    {
        $this->newDetail = [
            'cont_dtl_order' => '',
            'service_id' => '',
            'person_role_id' => '1',
            'cont_dtl_title' => '',
            'cont_dtl_unit_price' => '',
            'cont_dtl_quantity' => '',
            'cont_dtl_unit' => '',
            'cont_dtl_tax_type' => '1',
            'cont_dtl_acc_item' => '売上',
            'notes' => '',
        ];
    }

    /**
     * check if the contract detail is empty
     */
    protected function isNewDetailEmpty()
    {
        return (
            !empty($this->newDetail['cont_dtl_order']) ||
            !empty($this->newDetail['cont_dtl_title']) ||
            !empty($this->newDetail['cont_dtl_unit_price']) ||
            !empty($this->newDetail['cont_dtl_quantity']) ||
            !empty($this->newDetail['cont_dtl_unit']) ||
            !empty($this->newDetail['notes'])
            ) ? false : true;
    }

    /**
     * saev Contract details
     */
    protected function saveDetails() {
        // 新規契約明細に入力が残っている場合はエラー表示
        if(!$this->isNewDetailEmpty()) {
            throw new \Exception('新規明細が追加されていません。');
        }
        // details 配列にない明細を削除
        $details = contractDetail::where('contract_id', $this->id)->get();
        foreach($details as $detail) {
            // 明細レコードのidが$this->detailsにない場合は削除
            if(!in_array($detail->id, array_column($this->details, 'id'))) {
                $detail->delete();
            }
        }
        // details配列の明細を保存
        foreach($this->details as $detail) {
            if(isset($detail['id'])) {
                contractDetail::find($detail['id'])->update($detail);
            } else {
                contractDetail::create(array_merge($detail, ['contract_id' => $this->id]));
            }
        }
    }

    /**
     * mount function
     */
    public function mount($client_id, $id = null)
    {
        $this->client = Client::find($client_id);
        if(!$this->client) {
            return redirect()->route('clients');
        }

        $this->services = Service::all();
        $this->personRoles = PersonRole::all();

        $this->id = $id;

        // read contract details
        $details = contractDetail::where('contract_id', $id)->get();
        foreach($details as $detail) {
            $this->details[] = [
                'id' => $detail->id,
                'contract_id' => $detail->contract_id,
                'cont_dtl_order' => $detail->cont_dtl_order,
                'service_id' => $detail->service_id,
                'person_role_id' => $detail->person_role_id,
                'cont_dtl_title' => $detail->cont_dtl_title,
                'cont_dtl_unit_price' => $detail->cont_dtl_unit_price,
                'cont_dtl_quantity' => $detail->cont_dtl_quantity,
                'cont_dtl_unit' => $detail->cont_dtl_unit,
                'cont_dtl_tax_type' => $detail->cont_dtl_tax_type,
                'cont_dtl_acc_item' => $detail->cont_dtl_acc_item,
                'notes' => $detail->notes,
                'isvalid' => $detail->isvalid,
            ];
        }
        // 新規明細の初期化
        $this->resetNewDetailFields();
    }

    /**
     * リスナー
     */
    protected $listeners = [
        'deleteDetailListener' => 'deleteDetail',
    ];

    abstract public function render();

    /**
     * Cancel add/edit form and redirect to the master list
     * @return void
     */
    public function cancelContract() {
        return redirect()->route('contracts', ['client_id' => $this->client->id]);
    }

    /**
     * 契約明細の追加
     */
    public function addNewDetail()
    {
        $this->validate([
            'newDetail.cont_dtl_order' => 'required|numeric',
            'newDetail.cont_dtl_title' => 'required',
        ]);

        $this->details[] = [
            'cont_id' => $this->id,
            'cont_dtl_order' => $this->newDetail['cont_dtl_order'],
            'service_id' => $this->newDetail['service_id'],
            'person_role_id' => $this->newDetail['person_role_id'],
            'cont_dtl_title' => $this->newDetail['cont_dtl_title'],
            'cont_dtl_unit_price' => $this->newDetail['cont_dtl_unit_price'],
            'cont_dtl_quantity' => $this->newDetail['cont_dtl_quantity'],
            'cont_dtl_unit' => $this->newDetail['cont_dtl_unit'],
            'cont_dtl_tax_type' => $this->newDetail['cont_dtl_tax_type'],
            'cont_dtl_acc_item' => $this->newDetail['cont_dtl_acc_item'],
            'notes' => $this->newDetail['notes'],
        ];

        $this->resetNewDetailFields();
    }

    /**
     * 新規明細欄のクリア
     */
    public function clearNewDetail()
    {
        $this->resetNewDetailFields();
    }

    /**
     * 契約明細の削除
     */
    public function deleteDetail($key) {
        unset($this->details[$key]);
    }

    /**
     * 契約明細の表示順が変更された
     */
    public function updateDetailOrder($key, $value)
    {
        $this->validate([
            'details.*.cont_dtl_order' => 'required|numeric',
        ]);
    }
    /**
     * 契約明細の契約単価が変更された
     */
    public function updateDetailUnitPrice($key, $value)
    {
        $value = $this->UtlStr2Number($value);
        $this->details[$key]['cont_dtl_unit_price'] = $this->UtlNumber2str($value, config('app.decimal_digits'));
    }
    /**
     * 契約明細の数量が変更された
     */
    public function updateDetailQuantity($key, $value)
    {
        $value = $this->UtlStr2Number($value);
        $this->details[$key]['cont_dtl_quantity'] = $this->UtlNumber2str($value, config('app.decimal_digits'));
    }

    /**
     * 契約明細の表示順が変更された
     */
    public function updateNewDetailOrder($value)
    {
        $this->validate([
            'newDetail.cont_dtl_order' => 'required|numeric',
        ]);
    }
    /**
     * 新規契約明細の単価が変更された
     */
    public function updateNewDetailUnitPrice($value)
    {
        $value = $this->UtlStr2Number($value);
        $this->newDetail['cont_dtl_unit_price'] = $this->UtlNumber2Str($value, config('app.decimal_digits'));
    }
    /**
     * 新規契約明細の数量が変更された
     */
    public function updateNewDetailQuantity($value)
    {
        $value = $this->UtlStr2Number($value);
        $this->newDetail['cont_dtl_quantity'] = $this->UtlNumber2Str($value, config('app.decimal_digits'));
    }
}
