<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Bill;

class BillEditCreate extends BillEditBase
{
    /**
     * reset fields
     */
    public function resetFields()
    {
        $this->bill_no = Bill::getNextBillNo();
        $this->client_id = '';
        $this->person_role_id = '1';
        $this->bill_title = '';
        $this->bill_date = date('Y-m-d');
        $this->payment_notice = '御社規定による';
        $this->show_ceo = '0';
        $this->notes = '';
        $this->isvalid = '1';
    }

    /**
     * mount function
     */
    public function mount($id = null)
    {
        parent::mount($id);
        $this->resetFields();
    }

    public function render()
    {
        return view('livewire.billEditCreate',[
            'clients' => $this->clients,
            'services' => $this->services,
            'personRoles' => $this->personRoles,
        ]);
    }

    /**
     * store function
     */
    public function storeBill()
    {
        $this->validate();

        // トランザクション開始
        \DB::beginTransaction();
        try {
            $bill = Bill::create([
                'bill_no' => $this->bill_no,
                'client_id' => $this->client_id,
                'person_role_id' => $this->person_role_id ? $this->person_role_id : 1,  // 緊急避難
                'bill_title' => $this->bill_title,
                'bill_date' => $this->bill_date,
                'payment_notice' => $this->payment_notice,
                'show_ceo' => $this->show_ceo ? true : false,
                'notes' => $this->notes,
                'isvalid' => $this->isvalid,
            ]);
            $this->id = $bill->id;
            $this->saveBillDetails();
            // コミット
            \DB::commit();
            $this->resetFields();
            session()->flash('message', '請求書を登録しました');
            return redirect()->route('bills');
        } catch (\Exception $e) {
            // ロールバック
            \DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }
}
