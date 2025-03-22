<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Estimate;

class EstimateEditCreate extends EstimateEditBase
{
    /**
     * 見積番号の自動採番
     */
    protected function getEstimateNo()
    {
        $yyyymm = date('Ym');
        $maxEstimateNo = Estimate::where('estimate_no', 'like', $yyyymm . '%')
            ->max('estimate_no');
        if (empty($maxEstimateNo)) {
            return $yyyymm . '-001';
        }
        return sprintf('%s-%03d', $yyyymm, intval(substr($maxEstimateNo, 7)) + 1);
    }

    /**
     * reset fields
     */
    public function resetFields()
    {
        $this->estimate_no = $this->getEstimateNo();
        $this->client_id = '';
        $this->estimate_title = '';
        $this->estimate_date = date('Y-m-d');
        $this->deliverly_date = '';
        $this->deliverly_place = '御社指定場所';
        $this->payment_notice = '御社規定による';
        $this->valid_until = '見積から1ヶ月';
        $this->show_ceo = '';
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
        return view('livewire.estimateEditCreate', [
            'clients' => $this->clients,
            'services' => $this->services,
            'personRoles' => $this->personRoles,
        ]);
    }

    /**
     * store input post data into the table
     */
    public function storeEstimate()
    {
        $this->validate();
        // トランザクション開始
        \DB::beginTransaction();
        try {
            $estimate = Estimate::create([
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
            $this->id = $estimate->id;
            $this->saveEstimateDetails();
            // トランザクション終了
            \DB::commit();
            session()->flash('message', 'Estimate created successfully.');
            return redirect()->route('estimates');
        } catch (\Exception $e) {
            // トランザクションロールバック
            \DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }
}
