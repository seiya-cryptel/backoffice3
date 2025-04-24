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
            <button wire:click="createContract()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold text-sm py-1 px-2 rounded">{{ __('Add') }}</button>
        </div>
        <!-- 検索窓の追加 -->
        <div>
            {{$client->cl_mstno}}{{$client->cl_full_name}}
            <input wire:model.live="showInvalid" type="checkbox" class="form-control text-sm py-1" id="retire" wire:change="changeShowInvalid($event.target.value)">
            無効も表示する
            <button wire:click="showClient()" class="bg-green-500 hover:bg-green-700 text-white font-bold text-sm py-1 px-2 rounded">{{ __('Client') }}</button>
            <button wire:click="showClientPersons()" class="bg-green-500 hover:bg-green-700 text-white font-bold text-sm py-1 px-2 rounded">{{ __('Person') }}</button>
        </div>
        <div>
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th>{{ __('Display Order') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Valid') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @if (count($Contracts) > 0)
                @foreach ($Contracts as $Contract)
                    <tr class="border-b">
                        <td>
                            {{$Contract->contract_order}}
                        </td>
                        <td>
                            {{$Contract->contract_title}}
                        </td>
                        <td>
                            {{$Contract->isvalid ? __('Valid') : __('Invalid')}}
                        </td>
                        <td>
                            <button wire:click="updateContract({{$Contract->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">{{ __('Edit') }}</button>
                            <button onclick="deleteContract({{$Contract->id}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">{{ __('Delete') }}</button>
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
        {{ $Contracts->links() }}
    </div>    
    <script>
        function deleteContract(id){
            if(confirm('Are you sure to delete this record?')){
                Livewire.dispatch('deleteContractListener', { id: id });
            }
        }
    </script>
</div>
