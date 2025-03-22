<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\ClientPerson;
use App\Models\Client;

class ClientPersonEditUpdate extends ClientPersonEditBase
{
    /**
     * load the client person data
     */
    protected function loadClientPerson()
    {
        $clientPerson = ClientPerson::find($this->id);
        if ($clientPerson) {
            $this->psn_order = $clientPerson->psn_order;
            $this->psn_name = $clientPerson->psn_name;
            $this->psn_email = $clientPerson->psn_email;
            $this->psn_zip = $clientPerson->psn_zip;
            $this->psn_addr1 = $clientPerson->psn_addr1;
            $this->psn_addr2 = $clientPerson->psn_addr2;
            $this->psn_tel = $clientPerson->psn_tel;
            $this->psn_fax = $clientPerson->psn_fax;
            $this->psn_branch = $clientPerson->psn_branch;
            $this->psn_title = $clientPerson->psn_title;
            $this->psn_keisho = $clientPerson->psn_keisho;
            $this->notes = $clientPerson->notes;
            $this->isvalid = $clientPerson->isvalid;
        }
    }

    /**
     * mount the component
     */
    public function mount($client_id, $id = null)
    {
        parent::mount($client_id, $id);
        $this->loadClientPerson();
    }

    public function render()
    {
        return view('livewire.clientPersonEditUpdate', [
            'Client' => $this->client,
            'PersonRoles' => $this->personRoles,
        ]);
    }

    /**
     * update the client person data
     */
    public function updateClientPerson()
    {
        $this->validate();
        try {
            ClientPerson::find($this->id)->update([
                'psn_order' => $this->psn_order,
                'psn_name' => $this->psn_name,
                'psn_email' => $this->psn_email,
                'psn_zip' => $this->psn_zip,
                'psn_addr1' => $this->psn_addr1,
                'psn_addr2' => $this->psn_addr2,
                'psn_tel' => $this->psn_tel,
                'psn_fax' => $this->psn_fax,
                'psn_branch' => $this->psn_branch,
                'psn_title' => $this->psn_title,
                'psn_keisho' => $this->psn_keisho,
                'notes' => $this->notes,
                'isvalid' => $this->isvalid,
            ]);

            $this->saveClientPersonRoles();

        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong.');
        }
        session()->flash('message', 'Client Person updated successfully.');
        return redirect()->route('clientPersons', ['client_id' => $this->client->id]);
    }
}
