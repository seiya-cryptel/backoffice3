<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Client;
use App\Models\ClientGroup;

abstract class ClientEditBase extends Component
{

    /**
     * Client Group List
     */
    protected $clientGroups;

    /**
     * fields
     */
    public $cl_mstno, $cl_full_name, $cl_short_name, $cl_kana_name, 
        $client_group_id,
        $cl_zip, $cl_addr1, $cl_addr2, $cl_tel, $cl_fax, 
        $notes, $isvalid;

    /**
     * id valud
     */
    public $id;

    /**
     * validation rules
     */
    protected $rules = [
        'cl_mstno' => 'required',
        'cl_full_name' => 'required',
        'cl_short_name' => 'required',
        'cl_kana_name' => 'required',
        'isvalid' => 'required',
    ];

    /**
     * mount function
     */
    public function mount($id = null)
    {
        $this->clientGroups = clientGroup::orderBy('cl_group_cd', 'asc')->get();
        $this->id = $id;
    }

    abstract public function render();

    /**
     * Cancel add/edit form and redirect to the master list
     * @return void
     */
    public function cancelClient() {
        return redirect()->route('clients');
    }

}
