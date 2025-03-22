<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;

use App\Models\ClientGroup;

class ClientGroups extends Component
{
    use WithPagination;

    /**
     * listener(s)
     */
    protected $listeners = ['deleteGroupListener' => 'deleteGroup'];
    
    public function render()
    {
        $ClientGroups = ClientGroup::orderBy('cl_group_cd', 'asc')
            ->paginate(config('app.pagination_limit'));
        return view('livewire.clientGroups', ['ClientGroups' => $ClientGroups]);
    }
}
