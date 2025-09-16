<?php

namespace App\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\DB;

use App\Models\ClientPerson;
use App\Models\Client;

class ClientPersonEditCreate extends ClientPersonEditBase
{
    /**
     * Reseting all the input fields
     * @return void
     */
    public function resetFields()
    {
        $this->psn_order = '';
        $this->psn_name = '';
        $this->psn_email = ''; 
        $this->psn_zip = '';
        $this->psn_addr1 = '';
        $this->psn_addr2 = '';
        $this->psn_tel = '';
        $this->psn_fax = '';
        $this->psn_branch = '';
        $this->psn_title = '';
        $this->psn_keisho = '';
        $this->notes = '';
        $this->isvalid = '1';
    }

    /**
     * mount function
     */
    public function mount($client_id, $id = null)
    {
        parent::mount($client_id, $id);
        $this->resetFields();
    }

    public function render()
    {
        return view('livewire.clientPersonEditCreate', [
            'Client' => $this->client,
            'PersonRoles' => $this->personRoles,
        ]);
    }

    /**
     * store input post data into the table
     */
    public function storeClientPerson()
    {
        $this->validate();
        try {
            // トランザクションの開始
            DB::beginTransaction();
            $clientPerson =
            ClientPerson::create([
                'client_id' => $this->client->id,
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
            $this->id = $clientPerson->id;
            
            $this->saveClientPersonRoles();
            // コミット
            DB::commit();
        } catch (\Exception $e) {
            // ロールバック
            DB::rollBack();
            $this->msgError = 'ClientPerson create error ' . $e->getMessage();
            return;
        }
        session()->flash('message', 'Client Person created successfully');
        return redirect()->route('clientPersons', ['client_id' => $this->client->id]);
    }
}
