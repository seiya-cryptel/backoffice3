<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

use App\Consts\AppConsts;

use App\Models\Contract;
use App\Models\Client;

/**
 * Class Contracts
 * @package App\Livewire
 * 
 * 顧客一覧
 */
class Contracts extends Component
{
    use WithPagination;

    /**
     * 顧客
     */
    public $client;

    /**
     * 無効表示フラグ
     */
    public bool $showInvalid = false;

    /**
     * mount function
     */
    public function mount($client_id)
    {
        if(empty($client_id)) {
            return redirect()->route('clients');
        }
        $this->client = Client::find($client_id);
        $this->showInvalid = session(AppConsts::SESS_SHOW_INVALID, false);
    }

    /**
     * listener(s)
     */
    protected $listeners = ['deleteContractListener' => 'deleteContract'];

    public function render()
    {
        $Query = Contract::query();
        // 無効表示
        if (! $this->showInvalid) {
            $Query->where('isvalid', '!=', '0')
                ->whereNotNull('isvalid');
        }
        $Query->where('client_id', $this->client->id);

        $Contracts = $Query->orderBy('contract_order', 'asc')
            ->paginate(config('app.pagination_limit'));

        return view('livewire.contracts', ['Contracts' => $Contracts]);
    }

    /**
     * open create form
     */
    public function createContract()
    {
        return redirect()->route('contractEditCreate', ['client_id' => $this->client->id]);
    }

    /**
     * open update form
     */
    public function updateContract($id)
    {
        return redirect()->route('contractEditUpdate', ['client_id' => $this->client->id, 'id' => $id]);
    }

    /**
     * mark as invalid
     */
    public function deleteContract($id)
    {
        $Contract = Contract::find($id);
        $Contract->isvalid = 0;
        $Contract->save();
    }

    /**
     * when the show invalid checkbox is clicked
     */
    public function updateShowInvalid()
    {
        session([AppConsts::SESS_SHOW_INVALID => $this->showInvalid]);
    }

    /**
     * show the client view
     */
    public function showClient()
    {
        return redirect()->route('clientEditUpdate', ['id' => $this->client->id]);
    }

    /**
     * change showInvalid flag
     */
    public function changeShowInvalid($value)
    {
        session([AppConsts::SESS_SHOW_INVALID => $this->showInvalid]);
    }
}
