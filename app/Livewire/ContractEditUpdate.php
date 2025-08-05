<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Contract;

class ContractEditUpdate extends ContractEditBase
{
    /**
     * load the contract data
     */
    protected function loadContract()
    {
        $contract = Contract::find($this->id);
        if ($contract) {
            $this->contract_order = $contract->contract_order;
            $this->contract_title = $contract->contract_title;
            $this->contract_start = $contract->contract_start;
            $this->contract_end = $contract->contract_end;
            $this->contract_service_in = $contract->contract_service_in;
            $this->contract_first_bill = $contract->contract_first_bill;
            $this->contract_end_bill = $contract->contract_end_bill;
            $this->contract_interval = $contract->contract_interval;
            $this->contract_month_ofs = $contract->contract_month_ofs;
            $this->contract_bill_month = $contract->contract_bill_month;
            $this->contract_bill_day = $contract->contract_bill_day;
            $this->contract_next_date = $contract->contract_next_date;
            $this->notes = $contract->notes;
            $this->isvalid = $contract->isvalid;
        }
    }

    /**
     * mount the component
     */
    public function mount($client_id, $id = null)
    {
        parent::mount($client_id, $id);
        $this->loadContract();
    }

    public function render()
    {
        return view('livewire.contractEditUpdate',[
            'Client' => $this->client,
            'Services' => $this->services,
            'PersonRoles' => $this->personRoles,
        ]);
    }

    /**
     * update the contract data
     */
    public function updateContract()
    {
        $this->validate();
        try {
            Contract::find($this->id)->update([
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
                'isvalid' => $this->isvalid,
            ]);

            $this->saveDetails();

            $logMessage = 'Contract updated by ' . auth()->user()->name;
            logger($logMessage);
            session()->flash('message', 'Contract updated successfully.');
            return redirect()->route('contracts', ['client_id' => $this->client->id]);
        } catch (\Exception $e) {
            $logMessage = 'Contract updated error ' . $e->getMessage();
            logger($logMessage);
            $this->msgError = 'Contract updated error ' . $e->getMessage();
        }
    }
}
