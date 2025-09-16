<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\ClientPerson;
use App\Models\PersonRole;
use App\Models\Client;
use App\Models\ClientPersonRole;

abstract class ClientPersonEditBase extends Component
{
    /**
     * person roles
     */
    public $personRoles;
    /**
     * Client
     */
    public $client;

    /**
     * fields
     */
    public $psn_order, $psn_name, $psn_email, 
        $psn_zip, $psn_addr1, $psn_addr2,$psn_tel,$psn_fax,$psn_branch,$psn_title,$psn_keisho,
        $notes, $isvalid;

    /**
     * id valud
     */
    public $id;

    /**
     * Error message
     */
    public $msgError;

    /**
     * person roles
     */
    public $clientPersonRoles = [];
    /**
     * new person role
     */
    public $newClientPersonRole = [];

    /**
     * validation rules
     */
    protected $rules = [
        'psn_order' => 'required',
        'psn_name' => 'required',
        'isvalid' => 'required',
    ];

    /**
     * mount function
     */
    public function mount($client_id, $id = null)
    {
        $this->client = Client::find($client_id);
        if(!$this->client) {
            return redirect()->route('clients');
        }

        $this->id = $id;

        $this->personRoles = PersonRole::all();

        // read person roles
        $clientPersonRoles = ClientPersonRole::where('client_person_id', $id)->get();
        foreach($clientPersonRoles as $clientPersonRole) {
            $this->clientPersonRoles[] = [
                'id' => $clientPersonRole->id,
                'person_role_id' => $clientPersonRole->person_role_id,
                'post_to' => $clientPersonRole->post_to,
                'notes' => $clientPersonRole->notes,
                'isvalid' => $clientPersonRole->isvalid,
            ];
        }
    }

    /**
     * リスナー
     */
    protected $listeners = ['deleteClientPersonRoleListener' => 'deleteClientPersonRole'];

    abstract public function render();

    /**
     * Cancel add/edit form and redirect to the master list
     * @return void
     */
    public function cancelClientPerson() {
        return redirect()->route('clientPersons', ['client_id' => $this->client->id]);
    }

    /**
     * 担当者役割の保存
     */
    protected function saveClientPersonRoles() {
        foreach($this->clientPersonRoles as $clientPersonRole) {
            if(empty($clientPersonRole['id'])) {
                ClientPersonRole::create([
                    'client_person_id' => $this->id,
                    'person_role_id' => $clientPersonRole['person_role_id'],
                    'post_to' => empty($clientPersonRole['post_to']) ? false : $clientPersonRole['post_to'],
                    'notes' => empty($clientPersonRole['notes']) ? '' : $clientPersonRole['notes'],
                    'isvalid' => true,
                ]);
            } else {
                ClientPersonRole::find($clientPersonRole['id'])->update([
                    'person_role_id' => $clientPersonRole['person_role_id'],
                    'post_to' => $clientPersonRole['post_to'],
                    'notes' => $clientPersonRole['notes'],
                    'isvalid' => true,
                ]);
            }
        }
    }

    /**
     * 担当者役割の追加
     */
    public function addClientPersonRole() {
        // 役割が選択されていない場合は無視
        if(empty($this->newClientPersonRole['person_role_id'])) {
            return;
        }
        // 役割が重複してはいけない
        foreach($this->clientPersonRoles as $clientPersonRole) {
            if($clientPersonRole['person_role_id'] == $this->newClientPersonRole['person_role_id']) {
                return;
            }
        }
        $this->clientPersonRoles[] = $this->newClientPersonRole;
        $this->newClientPersonRole = [];
    }

    /**
     * 担当者役割の削除
     */
    public function deleteClientPersonRole($key) {
        unset($this->clientPersonRoles[$key]);
    }
}
