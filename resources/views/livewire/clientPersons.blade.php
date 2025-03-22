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
            <button wire:click="createClientPerson()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold text-sm py-1 px-2 rounded">{{ __('Add') }}</button>
        </div>
        <!-- 検索窓の追加 -->
        <div>
            {{$client->cl_mstno}}{{$client->cl_full_name}}
            <input wire:model.live="showInvalid" type="checkbox" class="form-control text-sm py-1" id="retire" wire:change="changeShowInvalid($event.target.value)">
            無効も表示する
            <button wire:click="showClient()" class="bg-green-500 hover:bg-green-700 text-white font-bold text-sm py-1 px-2 rounded">{{ __('Client') }}</button>
            <button wire:click="showClientContracts()" class="bg-green-500 hover:bg-green-700 text-white font-bold text-sm py-1 px-2 rounded">{{ __('Contract') }}</button>
        </div>
        <div>
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th>{{ __('Order') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('EMail') }}</th>
                        <th>{{ __('Valid') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @if (count($ClientPersons) > 0)
                @foreach ($ClientPersons as $ClientPerson)
                    <tr class="border-b">
                    <td>
                            {{$ClientPerson->psn_order}}
                        </td>
                        <td>
                            {{$ClientPerson->psn_name}}
                        </td>
                        <td>
                            {{$ClientPerson->psn_email}}
                        </td>
                        <td>
                            {{$ClientPerson->isvalid ? __('Valid') : __('Invalid')}}
                        </td>
                        <td>
                            <button wire:click="updateClientPerson({{$ClientPerson->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">{{ __('Edit') }}</button>
                            <button onclick="deleteClientPerson({{$ClientPerson->id}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">{{ __('Delete') }}</button>
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
        {{ $ClientPersons->links() }}
    </div>    
    <script>
        function deleteClientPerson(id){
            if(confirm(__('Are you sure to delete this record?'))){
                Livewire.dispatch('deleteClientPersonListener', { id: id });
            }
        }
    </script>
</div>
