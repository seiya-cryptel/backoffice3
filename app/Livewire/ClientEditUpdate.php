<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Client;

class ClientEditUpdate extends ClientEditBase
{
    /**
     * load the client data
     */
    protected function loadClient()
    {
        $client = Client::find($this->id);
        if ($client) {
            $this->cl_mstno = $client->cl_mstno;
            $this->cl_full_name = $client->cl_full_name;
            $this->cl_short_name = $client->cl_short_name;
            $this->cl_kana_name = $client->cl_kana_name;
            $this->client_group_id = $client->client_group_id;
            $this->cl_zip = $client->cl_zip;
            $this->cl_addr1 = $client->cl_addr1;
            $this->cl_addr2 = $client->cl_addr2;
            $this->cl_tel = $client->cl_tel;
            $this->cl_fax = $client->cl_fax;
            $this->notes = $client->notes;
            $this->isvalid = $client->isvalid;
        }
    }

    /**
     * mount the component
     */
    public function mount($id = null)
    {
        parent::mount($id);
        $this->loadClient();
    }

    public function render()
    {
        return view('livewire.clientEditUpdate', [
            'clientGroups' => $this->clientGroups,
        ]);
    }

    /** */

    /**
     * update the client data
     * @return void
     */
    public function updateClient()
    {
        $this->validate();
        try {
            Client::find($this->id)->update([
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
            session()->flash('success', __('Update'). ' ' . __('Done'));
            return redirect()->route('clients');
        } catch (\Exception $e) {
            session()->flash('error', 'Client update failed.');
        }
    }
}
