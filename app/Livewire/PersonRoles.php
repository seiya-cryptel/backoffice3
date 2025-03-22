<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;

use App\Models\PersonRole;

class PersonRoles extends Component
{
    use WithPagination;

    /**
     * listener(s)
     */
    protected $listeners = ['deleteRoleListener' => 'deleteRole'];
    
    public function render()
    {
        $PersonRoles = PersonRole::orderBy('role_cd', 'asc')
            ->paginate(config('app.pagination_limit'));
        return view('livewire.personRoles', ['PersonRoles' => $PersonRoles]);
    }
}
