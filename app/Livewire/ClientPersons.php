<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;

use App\Consts\AppConsts;

use App\Models\ClientPerson;
use App\Models\Client;

class ClientPersons extends Component
{
    use WithPagination;

    /**
     * 顧客
     */
    public $client_id;
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
        $this->client_id = $client_id;
        $this->client = Client::find($client_id);
        $this->showInvalid = session(AppConsts::SESS_SHOW_INVALID, false);
    }

    /**
     * listener(s)
     */
    protected $listeners = ['deleteClientPersonListener' => 'deleteClientPerson'];

    public function render()
    {
        $Query = ClientPerson::query();
        // 無効表示
        if (! $this->showInvalid) {
            $Query->where('isvalid', '!=', '0')
                ->whereNotNull('isvalid');
        }
        $Query->where('client_id', $this->client_id);

        $ClientPersons = $Query->orderBy('psn_order', 'asc')
            ->paginate(config('app.pagination_limit'));

        return view('livewire.clientPersons', ['ClientPersons' => $ClientPersons]);
    }

    /**
     * Open the create form
     */
    public function createClientPerson()
    {
        return redirect()->route('clientPersonEditCreate', ['client_id' => $this->client_id]);
    }
    /**
     * Open the update form
     */
    public function updateClientPerson($id)
    {
        return redirect()->route('clientPersonEditUpdate', ['client_id' => $this->client_id, 'id' => $id]);
    }
    /**
     * Mark the client person as invalid
     */
    public function deleteClientPerson($id)
    {
        $ClientPerson = ClientPerson::find($id);
        $ClientPerson->isvalid = 0;
        $ClientPerson->save();
    }

    /**
     * when show invalid flag changed
     */
    public function updatedShowInvalid()
    {
        session([AppConsts::SESS_SHOW_INVALID => $this->showInvalid]);
    }

    /**
     * show client view
     */
    public function showClient()
    {
        return redirect()->route('clientEditUpdate', ['id' => $this->client_id]);
    }
}
