<div>
    <div class="col-md-8 mb-2">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('success') }}
            </div>
        @endif                
        @if(session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('error') }}
            </div>
        @endif
    </div>
    <div class="col-md-8">
        <div class="text-right">            
            <button wire:click="newRate()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold text-sm py-1 px-2 rounded">{{ __('Tax Rate') . __('Add') }}</button>
        </div>
        <div>
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Rate') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @if (count($TaxRates) > 0)
                @foreach ($TaxRates as $TaxRate)
                    <tr class="border-b">
                        <td>
                            {{$TaxRate->tax_date}}
                        </td>
                        <td>
                            {{$TaxRate->tax_type}}
                        </td>
                        <td>
                            {{$TaxRate->tax_rate1}}
                        </td>
                        <td>
                            <button wire:click="editRate({{$TaxRate->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">{{ __('Edit') }}</button>
                            <button onclick="deleteRate({{$TaxRate->id}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">{{ __('Delete') }}</button>
                        </td>
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="3" align="center">
                            No Record Found.
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        {{ $TaxRates->links() }}
    </div>    
    <script>
        function deleteRate(id){
            if(confirm(__('Are you sure to delete this record?'))){
                Livewire.dispatch('deleteRateListener', { id: id });
            }
        }
    </script>
</div>
