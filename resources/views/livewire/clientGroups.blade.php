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
            <button wire:click="newRole()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold text-sm py-1 px-2 rounded">{{ __('Client Group') . __('Add') }}</button>
        </div>
        <div>
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th>{{ __('Code') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @if (count($ClientGroups) > 0)
                @foreach ($ClientGroups as $ClientGroup)
                    <tr class="border-b">
                    <td>
                            {{$ClientGroup->cl_group_cd}}
                        </td>
                        <td>
                            {{$ClientGroup->cl_group_name}}
                        </td>
                        <td>
                            <button wire:click="editGroup({{$ClientGroup->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">{{ __('Edit') }}</button>
                            <button onclick="deleteGroup({{$ClientGroup->id}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">{{ __('Delete') }}</button>
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
        {{ $ClientGroups->links() }}
    </div>    
    <script>
        function deleteGroup(id){
            if(confirm(__('Are you sure to delete this record?'))){
                Livewire.dispatch('deleteGroupListener', { id: id });
            }
        }
    </script>
</div>
