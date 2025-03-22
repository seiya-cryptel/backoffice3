<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;

use App\Models\AppSetting;

class AppSettings extends Component
{
    use WithPagination;

    /**
     * listener(s)
     */
    protected $listeners = ['deleteSettingListener' => 'deleteSetting'];
    
    public function render()
    {
        $AppSettings = AppSetting::orderBy('sys_name', 'asc')
            ->orderBy('sys_index', 'asc')
            ->paginate(config('app.pagination_limit'));
        return view('livewire.appSettings', ['AppSettings' => $AppSettings]);
    }
}
