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
            <button wire:click="newService()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold text-sm py-1 px-2 rounded">{{ __('Service') . __('Add') }}</button>
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
                @if (count($Services) > 0)
                @foreach ($Services as $Service)
                    <tr class="border-b">
                    <td>
                            {{$Service->svc_cd}}
                        </td>
                        <td>
                            {{$Service->svc_name}}
                        </td>
                        <td>
                            <button wire:click="editService({{$Service->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">{{ __('Edit') }}</button>
                            <button onclick="deleteService({{$Service->id}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">{{ __('Delete') }}</button>
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
        {{ $Services->links() }}
    </div>    
    <script>
        function deleteService(id){
            if(confirm(__('Are you sure to delete this record?'))){
                Livewire.dispatch('deleteServiceListener', { id: id });
            }
        }
    </script>
</div>
