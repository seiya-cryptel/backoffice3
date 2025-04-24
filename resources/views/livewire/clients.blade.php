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
            <button wire:click="newClient()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold text-sm py-1 px-2 rounded">{{ __('Client') . __('Add') }}</button>
        </div>
        <!-- 検索窓の追加 -->
        <div>
            <input wire:model.live="search" type="text" class="form-control text-sm py-1" id="search" placeholder="{{ __('Search Employees...') }}" wire:change="changeSearch($event.target.value)">
            <span>
                <button wire:click="clearSearch()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">X</button>
            </span>
            <input wire:model.live="showInvalid" type="checkbox" class="form-control text-sm py-1" id="retire" wire:change="changeShowInvalid($event.target.value)">
            無効も表示する
        </div>
        <div>
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th>{{ __('Code') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Valid') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @if (count($Clients) > 0)
                @foreach ($Clients as $Client)
                    <tr class="border-b">
                    <td>
                            {{$Client->cl_mstno}}
                        </td>
                        <td>
                            {{$Client->cl_short_name}}
                        </td>
                        <td>
                            {{$Client->isvalid ? __('Valid') : __('Invalid')}}
                        </td>
                        <td>
                            <button wire:click="editClient({{$Client->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">{{ __('Edit') }}</button>
                            <button wire:click="showClientPersons({{$Client->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">{{ __('Person') }}</button>
                            <button wire:click="showClientContracts({{$Client->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">{{ __('Contract') }}</button>
                            <button onclick="deleteClient({{$Client->id}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">{{ __('Delete') }}</button>
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
        {{ $Clients->links() }}
    </div>    
    <script>
        function deleteClient(id){
            if(confirm('Are you sure to delete this record?')){
                Livewire.dispatch('deleteClientListener', { id: id });
            }
        }
    </script>
</div>
