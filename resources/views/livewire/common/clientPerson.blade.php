<tr class="border-b">
    <td colspan="2" class="text-lg">
        {{ $Client->cl_mstno }} {{ $Client->cl_full_name }}
        @if ($msgError)
            <div class="alert alert-danger">{{ $msgError }}</div>
        @endif    
    </td>
</tr>
<tr class="border-b">
    <th><label for="psn_order">{{ __('Display Order') }}</label><span style="color: red;">*</span></th>
    <td>
        <input type="text" class="form-control @error('psn_order') is-invalid @enderror text-sm py-1" id="psn_order" wire:model="psn_order">
        @error('psn_order') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="psn_name">{{ __('Person') }}{{ __('Name') }}</label><span style="color: red;">*</span></th>
    <td>
        <input type="text" class="form-control @error('psn_name') is-invalid @enderror text-sm py-1" id="psn_name" wire:model="psn_name">
        @error('psn_name') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="psn_email">{{ __('Person') }}{{ __('Email') }}</label><span style="color: red;">*</span></th>
    <td>
        <input type="text" class="form-control @error('psn_email') is-invalid @enderror text-sm py-1" id="psn_email" wire:model="psn_email">
        @error('psn_email') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="psn_zip">{{ __('Zip Code') }}</label><span style="color: red;">*</span></th>
    <td>
        <input type="text" class="form-control @error('psn_zip') is-invalid @enderror text-sm py-1" id="psn_zip" wire:model="psn_zip">
        @error('psn_zip') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="psn_addr1">{{ __('Address') }}1</label><span style="color: red;">*</span></th>
    <td>
        <input type="text" class="form-control @error('psn_addr1') is-invalid @enderror text-sm py-1" id="psn_addr1" wire:model="psn_addr1">
        @error('psn_addr1') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="psn_addr2">{{ __('Address') }}2</label><span style="color: red;">*</span></th>
    <td>
        <input type="text" class="form-control @error('psn_addr2') is-invalid @enderror text-sm py-1" id="psn_addr2" wire:model="psn_addr2">
        @error('psn_addr2') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="psn_tel">{{ __('Phone') }}</label></th>
    <td>
        <input type="Text" class="form-control @error('psn_tel') is-invalid @enderror text-sm py-1" id="psn_tel" wire:model="psn_tel">
        @error('psn_tel') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="psn_fax">{{ __('Fax') }}</label></th>
    <td>
        <input type="Text" class="form-control @error('psn_fax') is-invalid @enderror text-sm py-1" id="psn_fax" wire:model="psn_fax">
        @error('psn_fax') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="psn_branch">{{ __('Branch') }}</label></th>
    <td>
        <input type="text" class="form-control @error('psn_branch') is-invalid @enderror text-sm py-1" id="psn_branch" wire:model="psn_branch">
        @error('psn_branch') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="psn_title">{{ __('Title') }}1</label></th>
    <td>
        <input type="text" class="form-control @error('psn_title') is-invalid @enderror text-sm py-1" id="psn_title" wire:model="psn_title">
        @error('psn_title') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="psn_keisho">{{ __('Keishou') }}</label></th>
    <td>
        <input type="text" class="form-control @error('psn_keisho') is-invalid @enderror text-sm py-1" id="psn_keisho" wire:model="psn_keisho">
        @error('psn_keisho') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="notes">{{ __('Notes') }}</label></th>
    <td>
        <input type="text" class="form-control @error('notes') is-invalid @enderror text-sm py-1" id="notes" wire:model="notes">
        @error('notes') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="isvalid">{{ __('Valid') }}</label></th>
    <td>
        <input type="checkbox" class="form-control @error('isvalid') is-invalid @enderror text-sm py-1" id="isvalid" wire:model="isvalid">
        @error('isvalid') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>

<tr class="border-b">
    <td colspan="2" class="text-sm">
        担当者の役割
    </td>
</tr>
@foreach($clientPersonRoles as $key => $value)
    <tr class="border-b">
        <th>{{ $key }}</th>
        <td>
            役割
            <select class="form-control @error('clientPersonRoles.{{$key}}.person_role_id') is-invalid @enderror text-sm py-1" id="clientPersonRoles.{{$key}}.person_role_id" wire:model="clientPersonRoles.{{$key}}.person_role_id">
                @foreach($PersonRoles as $PersonRole)
                    <option value="{{ $PersonRole->id }}">{{ $PersonRole->role_cd }} {{ $PersonRole->role_name }}</option>
                @endforeach
            </select>
            @error('clientPersonRoles'.$key.'person_role_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            通知
            <input type="checkbox" class="form-control @error('clientPersonRoles.{{$key}}.post_to') is-invalid @enderror text-sm py-1" id="clientPersonRoles.{{$key}}.post_to" wire:model="clientPersonRoles.{{$key}}.post_to">
            @error('clientPersonRoles'.$key.'post_to') 
                <span class="text-danger">{{ $message }}</span>
            @enderror
            備考
            <input type="text" class="form-control @error('clientPersonRoles.{{$key}}.notes') is-invalid @enderror text-sm py-1" id="clientPersonRoles.{{$key}}.notes" wire:model="clientPersonRoles.{{$key}}.notes">
            @error('clientPersonRoles'.$key.'notes') 
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <span onclick="deleteClientPersonRole({{$key}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">{{ __('Delete') }}</span>
        </td>
    </tr>
@endforeach

<tr class="border-b">
    <th>(新規)</th>
        <td>
            役割
            <select class="form-control @error('newClientPersonRole.person_role_id') is-invalid @enderror text-sm py-1" id="newClientPersonRole.person_role_id" wire:model="newClientPersonRole.person_role_id">
                <option value=""></option>
                @foreach($PersonRoles as $PersonRole)
                    <option value="{{ $PersonRole->id }}">{{ $PersonRole->role_cd }} {{ $PersonRole->role_name }}</option>
                @endforeach
            </select>
            @error('newClientPersonRole.person_role_id') 
                <span class="text-danger">{{ $message }}</span>
            @enderror
            通知
            <input type="checkbox" class="form-control @error('newClientPersonRole.post_to') is-invalid @enderror text-sm py-1" id="newClientPersonRole.post_to" wire:model="newClientPersonRole.post_to">
            @error('newClientPersonRole.post_to') 
                <span class="text-danger">{{ $message }}</span>
            @enderror
            備考
            <input type="text" class="form-control @error('newClientPersonRole.notes') is-invalid @enderror text-sm py-1" id="newClientPersonRole.notes" wire:model="newClientPersonRole.notes">
            @error('newClientPersonRole.notes') 
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <span wire:click="addClientPersonRole()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">{{ __('Add') }}</span>
        </td>
    </tr>
    <script>
        function deleteClientPersonRole(key){
            if(confirm('Are you sure to delete this record?')){
                Livewire.dispatch('deleteClientPersonRoleListener', { key: key });
            }
        }
    </script>
