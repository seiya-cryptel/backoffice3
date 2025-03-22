<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

use App\Consts\AppConsts;

use App\Services\SpreadSheetService;

use App\Models\Estimate;

class Estimates extends Component
{
    use WithPagination;

    /**
     * search keyword
     */
    public $search = '';

    /**
     * 見積を複製する
     * @param int $id   見積ID
     * @return void
     */
    protected function duplicateEstimate($id)
    {
        $Estimate = Estimate::find($id);
        $NewEstimate = $Estimate->duplicate();
        return $NewEstimate;
    }

    /**
     * 見積から請求を作成する
     * @param int $id   見積ID
     * @return void
     */
    protected function createBillFromEstimate($id)
    {
        $Estimate = Estimate::find($id);
        $Bill = $Estimate->createBill();
        return $Bill;
    }

    /**
     * mount function
     */
    public function mount()
    {
        $this->search = session(AppConsts::SESS_SEARCH, '');
    }

    /**
     * listener(s)
     */
    protected $listeners = [
        'deleteEstimateListener' => 'deleteEstimate',
    ];

    public function render()
    {
        $Query = Estimate::query()
        ->with('client')
        ->with('estimateDetails');

        // 文字列検索
        if(! empty($this->search)) {
            $Query->where(function($query) {
                $query->where('estimate_no', 'like', '%'.$this->search.'%')
                    ->orWhereHas('client', function($query) {
                        $query->where('cl_full_name', 'like', '%'.$this->search.'%')
                            ->orWhere('cl_short_name', 'like', '%'.$this->search.'%')
                            ->orWhere('cl_kana_name', 'like', '%'.$this->search.'%')
                            ->orWhere('cl_addr1', 'like', '%'.$this->search.'%')
                            ->orWhere('cl_addr2', 'like', '%'.$this->search.'%');
                    })
                    ->orWhere('estimate_title', 'like', '%'.$this->search.'%')
                    ->orWhere('notes', 'like', '%'.$this->search.'%')
                    ;
            });
        }

        $Estimates = $Query->orderBy('estimate_no', 'desc')
            ->limit(config('app.query_get_limit'))
            ->paginate(config('app.pagenation_limit'));

        return view('livewire.estimates', ['Estimates' => $Estimates]);
    }

    /**
     * open create form
     */
    public function createEstimate()
    {
        return redirect()->route('estimateEditCreate');
    }
    /**
     * open update form
     */
    public function updateEstimate($id)
    {
        return redirect()->route('estimateEditUpdate', ['id' => $id]);
    }
    /**
     * delete estimate
     */
    public function deleteEstimate($id)
    {
        $Estimate = Estimate::find($id);
        $Estimate->delete();
    }
    /**
     * copy estimate
     */
    public function copyEstimate($id)
    {
        // トランザクション開始
        \DB::beginTransaction();
        try {
            // 見積から請求を作成する
            $estimate = $this->duplicateEstimate($id);
            // トランザクション終了
            \DB::commit();
            session()->flash('success', '見積書を複製しました。');
            return redirect()->route('estimates');
        } catch (\Exception $e) {
            // トランザクションロールバック
            \DB::rollBack();
            session()->flash('error', '見積書の複製に失敗しました。' . $e->getMessage());
        }
    }
    /**
     * generate bill from estimate
     */
    public function generateBill($id)
    {
        // トランザクション開始
        \DB::beginTransaction();
        try {
            // 見積から請求を作成する
            $bill = $this->createBillFromEstimate($id);
            // トランザクション終了
            \DB::commit();
            session()->flash('success', '請求書を作成しました。');
            return redirect()->route('billEditUpdate', ['id' => $bill->id]);
        } catch (\Exception $e) {
            // トランザクションロールバック
            \DB::rollBack();
            session()->flash('error', '請求書の作成に失敗しました。' . $e->getMessage());
        }
    }
    /**
     * download estimate pdf
     */
    public function downloadEstimatePdf($id)
    {
        $service = new SpreadSheetService();
        try {
            session()->flash('success', '見積書 PDF を作成します。');
            return $service->exportEstimatePdf($id);
        } catch (\Exception $e) {
            session()->flash('error', '見積書 PDF の作成に失敗しました。' . $e->getMessage());
        }
    }
    /**
     * download estimate excel
     */
    public function downloadEstimateExcel($id)
    {
        $service = new SpreadSheetService();
        try {
            session()->flash('success', '見積書 Excel を作成します。');
            return $service->exportEstimateExcel($id);
        } catch (\Exception $e) {
            session()->flash('error', '見積書 Excel の作成に失敗しました。' . $e->getMessage());
        }
    }

    /**
     * change search keyword
     */
    public function changeSearch()
    {
        session([AppConsts::SESS_SEARCH => $this->search]);
    }

    /**
     * clear search keyword
     */
    public function clearSearch()
    {
        $this->search = '';
        session([AppConsts::SESS_SEARCH => '']);
    }
}
