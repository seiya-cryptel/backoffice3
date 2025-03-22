<tr class="border-b">
    <th><label for="cl_mstno">{{ __('Client Code') }}</label><span style="color: red;">*</span></th>
    <td>
        <input type="text" class="form-control @error('cl_mstno') is-invalid @enderror text-sm py-1" id="cl_mstno" wire:model="cl_mstno">
        @error('cl_mstno') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="cl_full_name">{{ __('Client Name') }}</label><span style="color: red;">*</span></th>
    <td>
        <input type="text" class="form-control @error('cl_full_name') is-invalid @enderror text-sm py-1" id="cl_full_name" wire:model="cl_full_name">
        @error('cl_full_name') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="cl_short_name">{{ __('Short Name') }}</label><span style="color: red;">*</span></th>
    <td>
        <input type="text" class="form-control @error('cl_short_name') is-invalid @enderror text-sm py-1" id="cl_short_name" wire:model="cl_short_name">
        @error('cl_short_name') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="cl_kana_name">{{ __('Client Kana') }}</label><span style="color: red;">*</span></th>
    <td>
        <input type="text" class="form-control @error('cl_kana_name') is-invalid @enderror text-sm py-1" id="cl_kana_name" wire:model="cl_kana_name">
        @error('cl_kana_name') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="client_group_id">{{ __('Client Group') }}</label></th>
    <td>
        <select class="form-control @error('client_group_id') is-invalid @enderror text-sm py-1" id="client_group_id" wire:model="client_group_id">
            <option value=""></option>
            @foreach($clientGroups as $clientGroup)
                <option value="{{ $clientGroup->id }}">{{ $clientGroup->cl_group_name }}</option>
            @endforeach
        </select>
        @error('client_group_id') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="cl_zip">{{ __('Client') }}{{ __('NBSP') }}{{ __('Zip Code') }}</label></th>
    <td>
        <input type="text" class="form-control @error('cl_zip') is-invalid @enderror text-sm py-1" id="cl_zip" wire:model="cl_zip">
        @error('cl_zip') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="cl_addr1">{{ __('Client') }}{{ __('NBSP') }}{{ __('Address') }}1</label></th>
    <td>
        <input type="text" class="form-control @error('cl_addr1') is-invalid @enderror text-sm py-1" id="cl_addr1" wire:model="cl_addr1">
        @error('cl_addr1') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="cl_addr2">{{ __('Client') }}{{ __('NBSP') }}{{ __('Address') }}2</label></th>
    <td>
        <input type="text" class="form-control @error('cl_addr2') is-invalid @enderror text-sm py-1" id="cl_addr2" wire:model="cl_addr2">
        @error('cl_addr2') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="cl_tel">{{ __('Client') }}{{ __('NBSP') }}{{ __('Phone') }}</label></th>
    <td>
        <input type="text" class="form-control @error('cl_tel') is-invalid @enderror text-sm py-1" id="cl_tel" wire:model="cl_tel">
        @error('cl_tel') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <th><label for="cl_fax">{{ __('Client') }}{{ __('NBSP') }}{{ __('Fax') }}</label></th>
    <td>
        <input type="text" class="form-control @error('cl_fax') is-invalid @enderror text-sm py-1" id="cl_fax" wire:model="cl_fax">
        @error('cl_fax') 
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
