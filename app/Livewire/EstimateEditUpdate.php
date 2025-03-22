<?php

namespace App\Livewire;

use Livewire\Component;

use App\Traits\Utilities;

use App\Models\Estimate;
use App\Models\estimateDetail;

class EstimateEditUpdate extends EstimateEditBase
{

    /**
     * read estimate
     */
    protected function readEstimate($id) {
        $estimate = Estimate::find($id);
        $this->estimate_no = $estimate->estimate_no;
        $this->client_id = $estimate->client_id;
        $this->estimate_title = $estimate->estimate_title;
        $this->estimate_date = $estimate->estimate_date;
        $this->deliverly_date = $estimate->deliverly_date;
        $this->deliverly_place = $estimate->deliverly_place;
        $this->payment_notice = $estimate->payment_notice;
        $this->valid_until = $estimate->valid_until;
        $this->show_ceo = $estimate->show_ceo;
        $this->notes = $estimate->notes;
        $this->isvalid = $estimate->isvalid;
    }

    /**
     * read Estimate details
     */
    protected function readEstimateDetails() {
        $estimateDetails = estimateDetail::where('estimate_id', $this->id)
            ->orderBy('estm_dtl_order', 'asc')
            ->get();
        foreach($estimateDetails as $estimateDetail) {
            $this->estimateDetails[] = [
                'id' => $estimateDetail->id,
                'estimate_id' => $estimateDetail->estimate_id,
                'estm_dtl_order' => $estimateDetail->estm_dtl_order,
                'service_id' => $estimateDetail->service_id,
                'person_role_id' => $estimateDetail->person_role_id,
                'estm_dtl_title' => $estimateDetail->estm_dtl_title,
                'estm_dtl_unit_price' => $estimateDetail->estm_dtl_unit_price,
                'estm_dtl_quantity' => $estimateDetail->estm_dtl_quantity,
                'estm_dtl_unit' => $estimateDetail->estm_dtl_unit,
                'estm_dtl_tax_type' => $estimateDetail->estm_dtl_tax_type,
                'estm_dtl_tax' => $estimateDetail->estm_dtl_tax,
                'estm_dtl_amount' => number_format($estimateDetail->estm_dtl_amount),
                'estm_dtl_acc_item' => $estimateDetail->estm_dtl_acc_item,
                'notes' => $estimateDetail->notes,
                'isvalid' => $estimateDetail->isvalid,
            ];
        }
    }

    /**
     * mount function
     */
    public function mount($id = null)
    {
        parent::mount($id);
        $this->readEstimate($id);
        $this->readEstimateDetails();
    }

    public function render()
    {
        return view('livewire.estimateEditUpdate', [
            'clients' => $this->clients,
            'services' => $this->services,
            'personRoles' => $this->personRoles,
        ]);
    }

    /**
     * update the estimate data
     */
    public function updateEstimate()
    {
        $this->validate();
        \DB::beginTransaction();
        try {
            Estimate::find($this->id)->update([
                'estimate_no' => $this->estimate_no,
                'client_id' => $this->client_id,
                'estimate_title' => $this->estimate_title,
                'estimate_date' => $this->estimate_date,
                'deliverly_date' => $this->deliverly_date,
                'deliverly_place' => $this->deliverly_place,
                'payment_notice' => $this->payment_notice,
                'valid_until' => $this->valid_until,
                'show_ceo' => $this->show_ceo,
                'notes' => $this->notes,
                'isvalid' => $this->isvalid,
            ]);
            $this->saveEstimateDetails();
            \DB::commit();
            session()->flash('message', 'Estimate updated successfully');
            return redirect()->route('estimates');
        } catch (\Exception $e) {
            \DB::rollback();
            session()->flash('error', $e->getMessage());
        }
    }
}
