<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;

use App\Consts\AppConsts;

use App\Models\Client;

/**
 * Clients List
 */
class Clients extends Component
{
    use WithPagination;

    /**
     * search keyword
     */
    public $search = '';

    /**
     * 退職者表示フラグ
     */
    public bool $showInvalid = false;

    /**
     * mount function
     */
    public function mount()
    {
        $this->search = session(AppConsts::SESS_SEARCH, '');
        $this->showInvalid = session(AppConsts::SESS_SHOW_INVALID, false);
    }

    /**
     * listener(s)
     */
    protected $listeners = ['deleteClientListener' => 'deleteClient'];
    
    public function render()
    {
        $Query = Client::query();
        // 無効表示
        if (! $this->showInvalid) {
            $Query->where('isvalid', '!=', '0')
                ->whereNotNull('isvalid');
        }
        // 文字列検索
        if(! empty($this->search)) {
            $Query->where(function($query) {
                $query->where('cl_mstno', 'like', '%'.$this->search.'%')
                    ->orWhere('cl_full_name', 'like', '%'.$this->search.'%')
                    ->orWhere('cl_short_name', 'like', '%'.$this->search.'%')
                    ->orWhere('cl_kana_name', 'like', '%'.$this->search.'%')
                    ->orWhere('cl_addr1', 'like', '%'.$this->search.'%')
                    ->orWhere('cl_addr2', 'like', '%'.$this->search.'%')
                    ->orWhere('notes', 'like', '%'.$this->search.'%')
                    ;
            });
        }

        $Clients = $Query->orderBy('cl_mstno', 'asc')
            ->paginate(config('app.pagination_limit'));
        return view('livewire.clients', ['Clients' => $Clients]);
    }

    /**
     * Open Add Client form
     * @return void
     */
    public function newClient()
    {
        return redirect()->route('clientEditCreate');
    }

    /**
     * Open Edit Client form
     * @return void
     */
    public function editClient($id)
    {
        // session([AppConsts::SESS_CLIENT_ID => $id]);
        return redirect()->route('clientEditUpdate', ['id' => $id]);
    }

    /**
     * Open Client Persons form
     */
    public function showClientPersons($id)
    {
        return redirect()->route('clientPersons', ['client_id' => $id]);
    }

    /**
     * Open Client Contacts form
     */
    public function showClientContracts($id)
    {
        return redirect()->route('contracts', ['client_id' => $id]);
    }

    /**
     * delete specific master data
     * @param mixed $id
     * @return void
     */
    public function deleteClient($id) {
        try {
            $Client = Client::find($id);
            $Client->isvalid = true;
            $Client->save();
            session()->flash('success', __('Invalidate') . ' ' . __('Done'));
        } catch (\Exception $e) {
            session()->flash('error', __('Something went wrong.'));
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

    /**
     * change showInvalid flag
     */
    public function changeShowInvalid($value)
    {
        session([AppConsts::SESS_SHOW_INVALID => $this->showInvalid]);
    }
}
