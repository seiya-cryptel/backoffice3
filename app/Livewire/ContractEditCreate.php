<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Contract;

class ContractEditCreate extends ContractEditBase
{
    /**
     * Reseting all the input fields
     * @return void
     */
    public function resetFields()
    {
        $this->contract_order = '';
        $this->contract_title = '';
        $this->contract_start = '';
        $this->contract_end = '';
        $this->contract_service_in = '';
        $this->contract_first_bill = '';
        $this->contract_end_bill = '';
        $this->contract_interval = '';
        $this->contract_month_ofs = '';
        $this->contract_bill_month = '';
        $this->contract_bill_day = '';
        $this->contract_next_date = '';
        $this->notes = '';
        $this->isvalid = '1';
    }

    /**
     * mount function
     */
    public function mount($client_id, $id = null)
    {
        parent::mount($client_id, $id);
        $this->resetFields();
    }

    /**
     * render function
     */
    public function render()
    {
        return view('livewire.contractEditCreate', [
            'Client' => $this->client,
            'Services' => $this->services,
            'PersonRoles' => $this->personRoles,
        ]);
    }

    /**
     * store input post data into the table
     * @return void
     */
    public function storeContract()
    {
        $this->validate();
        try {
            $contract = Contract::create([
                'client_id' => $this->client->id,
                'contract_order' => $this->contract_order,
                'contract_title' => $this->contract_title,
                'contract_start' => $this->contract_start,
                'contract_end' => $this->contract_end,
                'contract_service_in' => $this->contract_service_in,
                'contract_first_bill' => $this->contract_first_bill,
                'contract_end_bill' => $this->contract_end_bill,
                'contract_interval' => $this->contract_interval,
                'contract_month_ofs' => $this->contract_month_ofs,
                'contract_bill_month' => $this->contract_bill_month,
                'contract_bill_day' => $this->contract_bill_day,
                'contract_next_date' => $this->contract_next_date,
                'notes' => $this->notes,
                'isvalid' => $this->isvalid
            ]);
            $this->id = $contract->id;

            $this->saveDetails();

            $logMessage = 'Contract stored by ' . auth()->user()->name;
            logger($logMessage);
            session()->flash('message', 'Contract updated successfully.');
            return redirect()->route('contracts', ['client_id' => $this->client->id]);
        } catch (\Exception $e) {
            $logMessage = 'Contract updated error ' . $e->getMessage();
            logger($logMessage);
            session()->flash('error', 'There was a problem updating the contract.');
        }
    }
}
