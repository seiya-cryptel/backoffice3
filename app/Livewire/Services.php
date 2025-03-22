<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;

use App\Models\service;

class Services extends Component
{
    use WithPagination;
    /**
     * listener(s)
     */
    protected $listeners = ['deleteServiceListener' => 'deleteService'];
    
    public function render()
    {
        $Services = Service::orderBy('svc_cd', 'asc')
            ->paginate(config('app.pagination_limit'));
        return view('livewire.services', ['Services' => $Services]);
    }
}
