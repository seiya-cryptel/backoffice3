<?php

namespace App\Livewire;

use Livewire\Component;

use App\Traits\Utilities;

use App\Models\Bill;
use App\Models\BillDetail;

class BillEditUpdate extends BillEditBase
{
    /**
     * read bill
     */
    protected function readBill() {
        $bill = Bill::find($this->id);
        $this->bill_no = $bill->bill_no;
        $this->client_id = $bill->client_id;
        $this->person_role_id = $bill->person_role_id;
        $this->bill_title = $bill->bill_title;
        $this->bill_date = $bill->bill_date;
        $this->payment_notice = $bill->payment_notice;
        $this->show_ceo = $bill->show_ceo;
        $this->notes = $bill->notes;
        $this->isvalid = $bill->isvalid;
    }
    /**
     * read Bill details
     */
    protected function readBillDetails() {
        $billDetails = BillDetail::where('bill_id', $this->id)
            ->orderBy('bill_dtl_order', 'asc')
            ->get();
        foreach($billDetails as $billDetail) {
            $this->billDetails[] = [
                'id' => $billDetail->id,
                'bill_id' => $billDetail->bill_id,
                'bill_dtl_order' => $billDetail->bill_dtl_order,
                'service_id' => $billDetail->service_id,
                'person_role_id' => $billDetail->person_role_id,
                'bill_dtl_title' => $billDetail->bill_dtl_title,
                'bill_dtl_unit_price' => $billDetail->bill_dtl_unit_price,
                'bill_dtl_quantity' => $billDetail->bill_dtl_quantity,
                'bill_dtl_unit' => $billDetail->bill_dtl_unit,
                'bill_dtl_tax_type' => $billDetail->bill_dtl_tax_type,
                'bill_dtl_tax' => $billDetail->bill_dtl_tax,
                'bill_dtl_amount' => number_format($billDetail->bill_dtl_amount),
                'bill_dtl_acc_item' => $billDetail->bill_dtl_acc_item,
                'notes' => $billDetail->notes,
                'isvalid' => $billDetail->isvalid,
            ];
        }
    }

    /**
     * mount function
     */
    public function mount($id = null) {
        parent::mount($id);
        $this->readBill();
        $this->readBillDetails();
    }

    public function render()
    {
        return view('livewire.billEditUpdate', [
            'clients' => $this->clients,
            'services' => $this->services,
            'personRoles' => $this->personRoles,
        ]);
    }

    /**
     * update function
     */
    public function updateBill() {
        $this->validate();
        // トランザクション開始
        \DB::beginTransaction();
        try {
            Bill::find($this->id)->update([
                'bill_no' => $this->bill_no,
                'client_id' => $this->client_id,
                'person_role_id' => $this->person_role_id,
                'bill_title' => $this->bill_title,
                'bill_date' => $this->bill_date,
                'payment_notice' => $this->payment_notice,
                'show_ceo' => $this->show_ceo,
                'notes' => $this->notes,
                'isvalid' => $this->isvalid,
            ]);
            $this->saveBillDetails();
            \DB::commit();
            session()->flash('message', '請求書を登録しました');
            return redirect()->route('bills');
        } catch (\Exception $e) {
            \DB::rollback();
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

}

