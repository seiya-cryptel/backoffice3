<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

use App\Consts\AppConsts;

use App\Services\SpreadSheetService;

use App\Models\Bill;
use App\Models\BillDetail;

class Bills extends Component
{
    use WithPagination;

    /**
     * search keyword
     */
    public $search = '';

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
        'deleteBillListener' => 'deleteBill',
    ];

    public function render()
    {
        $Query = Bill::query()
        ->with('client')
        ->with('billDetails');

        // 文字列検索
        if(! empty($this->search)) {
            $Query->where(function($query) {
                $query->where('bill_no', 'like', '%'.$this->search.'%')
                    ->orWhereHas('client', function($query) {
                        $query->where('cl_full_name', 'like', '%'.$this->search.'%')
                            ->orWhere('cl_short_name', 'like', '%'.$this->search.'%')
                            ->orWhere('cl_kana_name', 'like', '%'.$this->search.'%')
                            ->orWhere('cl_addr1', 'like', '%'.$this->search.'%')
                            ->orWhere('cl_addr2', 'like', '%'.$this->search.'%');
                    })
                    ->orWhere('bill_title', 'like', '%'.$this->search.'%')
                    ->orWhere('notes', 'like', '%'.$this->search.'%')
                    ;
            });
        }

        $Bills = $Query->orderBy('bill_no', 'desc')
            ->limit(config('app.query_get_limit'))
            ->paginate(config('app.pagination_limit'));

        return view('livewire.bills', ['Bills' => $Bills]);
    }

    /**
     * open create form
     */
    public function createBill()
    {
        return redirect()->route('billEditCreate');
    }

    /**
     * open update form
     */
    public function updateBill($id)
    {
        return redirect()->route('billEditUpdate', ['id' => $id]);
    }

    /**
     * copy bill
     */
    public function copyBill($id)
    {
        $originalBill = Bill::find($id);
        if(empty($originalBill)) {
            session()->flash('error', '請求書が見つかりません。');
            return;
        }
        $Bill = $originalBill->replicate();
        $Bill->bill_no = bill::getNextBillNo();
        $Bill->bill_title = $Bill->bill_title . ' (コピー)';
        $Bill->bill_date = date('Y-m-d');
        $Bill->save();

        foreach ($originalBill->billDetails as $originalDetail) {
            $Detail = $originalDetail->replicate();
            $Detail->bill_id = $Bill->id;
            $Detail->save();
        }
    }

    /**
     * delete bill
     */
    public function deleteBill($id)
    {
        $Bill = Bill::find($id);
        if(empty($Bill)) {
            session()->flash('error', '請求書が見つかりません。');
            return;
        }
        BillDetail::where('bill_id', $id)->delete();
        $Bill->delete();
    }

    /**
     * download bill as pdf
     */
    public function downloadBillPdf($id)
    {
        $Bill = Bill::find($id);
        if(! empty($Bill)) {
            try {
                $SpreadSheetService = new SpreadSheetService();
                session()->flash('success', '請求書 PDF を作成します。');
                return $SpreadSheetService->exportBillPdf($id);
            } catch (\Exception $e) {
                session()->flash('error', $e->getMessage());
            }
        }
    }
    /**
     * download bill as excel
     */
    public function downloadBillExcel($id)
    {
        $Bill = Bill::find($id);
        if(! empty($Bill)) {
            try {
                $SpreadSheetService = new SpreadSheetService();
                session()->flash('success', '請求書 Excel を作成します。');
                return $SpreadSheetService->exportBillExcel($id);
            } catch (\Exception $e) {
                session()->flash('error', $e->getMessage());
            }
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
