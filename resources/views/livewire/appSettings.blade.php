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
            <button wire:click="newSetting()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold text-sm py-1 px-2 rounded">{{ __('Settings') . __('Add') }}</button>
        </div>
        <div>
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Index') }}</th>
                        <th></th>
                        <th>{{ __('Number') }}</th>
                        <th>{{ __('Text') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @if (count($AppSettings) > 0)
                @foreach ($AppSettings as $AppSetting)
                    <tr class="border-b">
                        <td>
                            {{$AppSetting->sys_name}}
                        </td>
                        <td>
                            {{$AppSetting->sys_index}}:
                        </td>
                        <td>
                            {{$AppSetting->istext}} 
                        </td>
                        <td>
                            {{$AppSetting->sys_numval}} 
                        </td>
                        <td>
                            {{$AppSetting->sys_txtval}} 
                        </td>
                        <td>
                            <button wire:click="editSetting({{$AppSetting->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">{{ __('Edit') }}</button>
                            <button onclick="deleteSetting({{$AppSetting->id}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">{{ __('Delete') }}</button>
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
        {{ $AppSettings->links() }}
    </div>    
    <script>
        function deleteSetting(id){
            if(confirm(__('Are you sure to delete this record?'))){
                Livewire.dispatch('deleteSettingListener', { id: id });
            }
        }
    </script>
</div>
