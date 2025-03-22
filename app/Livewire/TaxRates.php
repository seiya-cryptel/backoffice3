<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;

use App\Models\TaxRate;

class TaxRates extends Component
{
    use WithPagination;

    /**
     * listener(s)
     */
    protected $listeners = ['deleteRateListener' => 'deleteRate'];
    
    public function render()
    {
        $TaxRates = TaxRate::orderBy('tax_date', 'asc')
            ->orderBy('tax_type', 'asc')
            ->paginate(config('app.pagination_limit'));
        return view('livewire.taxRates', ['TaxRates' => $TaxRates]);
    }
}
