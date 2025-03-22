<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Client;

class ClientEditCreate extends ClientEditBase
{

    /**
     * Reseting all the input fields
     * @return void
     */
    public function resetFields()
    {
        $this->cl_mstno = '';
        $this->cl_full_name = '';
        $this->cl_short_name = '';
        $this->cl_kana_name = '';
        $this->client_group_id = '';
        $this->cl_zip = '';
        $this->cl_addr1 = '';
        $this->cl_addr2 = '';
        $this->cl_tel = '';
        $this->cl_fax = '';
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

    /**
     * render function
     */
    public function render()
    {
        $this->resetFields();
        return view('livewire.clientEditCreate', [
            'clientGroups' => $this->clientGroups,
        ]);
    }

    /**
     * store input post data into the table
     * @return void
     */
    public function storeClient()
    {
        $this->validate();
        try {
            Client::create([
            'cl_mstno' => $this->cl_mstno,
            'cl_full_name' => $this->cl_full_name,
            'cl_short_name' => $this->cl_short_name,
            'cl_kana_name' => $this->cl_kana_name,
            'client_group_id' => $this->client_group_id,
            'cl_zip' => $this->cl_zip,
            'cl_addr1' => $this->cl_addr1,
            'cl_addr2' => $this->cl_addr2,
            'cl_tel' => $this->cl_tel,
            'cl_fax' => $this->cl_fax,
            'notes' => $this->notes,
            'isvalid' => $this->isvalid,
            ]);
            session()->flash('message', 'Client successfully created.');
            return redirect()->route('clients');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}
