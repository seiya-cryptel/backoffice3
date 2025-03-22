<tr class="border-b">
    <td colspan="10" class="text-lg">
        {{ $Client->cl_mstno }} {{ $Client->cl_full_name }}
    </td>
</tr>
<tr class="border-b">
    {{-- 表示順 --}}
    <td style="width: 8rem;" class="text-right"><label for="contract_order">{{ __('Display Order') }}</label><span style="color: red;">*</span></td>
    <td style="width: 8rem;">
        <input type="text" style="width: 4rem;" class="form-control @error('contract_order') is-invalid @enderror text-sm py-1 text-center" id="contract_order" wire:model="contract_order">
        @error('contract_order') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 契約名 --}}
    <td style="width: 8rem;" class="text-right"><label for="contract_title">{{ __('Contract Title') }}</label><span style="color: red;">*</span></td>
    <td colspan="7">
        <input type="text" style="width: 56rem;" class="form-control @error('contract_title') is-invalid @enderror text-sm py-1" id="contract_title" wire:model="contract_title">
        @error('contract_title') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    {{-- 契約開始日 --}}
    <td style="width: 8rem;" class="text-right"><label for="contract_start">{{ __('Contract Start') }}</label></td>
    <td style="width: 8rem;">
        <input type="text" style="width: 8rem;" class="form-control @error('contract_start') is-invalid @enderror text-sm py-1" id="contract_start" wire:model="contract_start">
        @error('contract_start') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 契約開始日 --}}
    <td style="width: 8rem;" class="text-right"><label for="contract_end">{{ __('Contract End') }}</label></td>
    <td style="width: 8rem;">
        <input type="text" style="width: 8rem;" class="form-control @error('contract_end') is-invalid @enderror text-sm py-1" id="contract_end" wire:model="contract_end">
        @error('contract_end') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- サービス開始日 --}}
    <td style="width: 8rem;" class="text-right"><label for="contract_service_in">{{ __('Contract Service In') }}</label></td>
    <td style="width: 8rem;">
        <input type="text" style="width: 8rem;" class="form-control @error('contract_service_in') is-invalid @enderror text-sm py-1" id="contract_service_in" wire:model="contract_service_in">
        @error('contract_service_in') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 初回請求日 --}}
    <td style="width: 8rem;" class="text-right"><label for="contract_first_bill">{{ __('Contract First Bill') }}</label></td>
    <td style="width: 8rem;">
        <input type="text" style="width: 8rem;" class="form-control @error('contract_first_bill') is-invalid @enderror text-sm py-1" id="contract_first_bill" wire:model="contract_first_bill">
        @error('contract_first_bill') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 最終請求日 --}}
    <td style="width: 8rem;" class="text-right"><label for="contract_end_bill">{{ __('Contract End Bill') }}</label></td>
    <td>
        <input type="text" style="width: 8rem;" class="form-control @error('contract_end_bill') is-invalid @enderror text-sm py-1" id="contract_end_bill" wire:model="contract_end_bill">
        @error('contract_end_bill') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    {{-- 繰り返し --}}
    <td style="width: 8rem;" class="text-right"><label for="contract_interval">{{ __('Contract Interval') }}</label></td>
    <td style="width: 8rem;">
        <input type="text" style="width: 4rem;" class="form-control @error('contract_interval') is-invalid @enderror text-sm py-1" id="contract_interval" wire:model="contract_interval">
        @error('contract_interval') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 対象月オフセット --}}
    <td style="width: 8rem;" class="text-right"><label for="contract_month_ofs">{{ __('Contract Month Offset') }}</label></td>
    <td style="width: 8rem;">
        <input type="text" style="width: 4rem;" class="form-control @error('contract_month_ofs') is-invalid @enderror text-sm py-1" id="contract_month_ofs" wire:model="contract_month_ofs">
        @error('contract_month_ofs') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 請求日オフセット --}}
    <td style="width: 8rem;" class="text-right"><label for="contract_bill_month">{{ __('Contract Bill Month') }}1</label></td>
    <td style="width: 8rem;">
        <input type="text" style="width: 4rem;" class="form-control @error('contract_bill_month') is-invalid @enderror text-sm py-1" id="contract_bill_month" wire:model="contract_bill_month">
        @error('contract_bill_month') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 請求日 --}}
    <td style="width: 8rem;" class="text-right"><label for="contract_bill_day">{{ __('Contract Bill Day') }}</label></td>
    <td style="width: 8rem;">
        <input type="text" style="width: 4rem;" class="form-control @error('contract_bill_day') is-invalid @enderror text-sm py-1" id="contract_bill_day" wire:model="contract_bill_day">
        @error('contract_bill_day') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 次回請求日 --}}
    <td style="width: 8rem;" class="text-right"><label for="contract_next_date">{{ __('Contract Next Date') }}</label></td>
    <td>
        <input type="text" style="width: 8rem;" class="form-control @error('contract_next_date') is-invalid @enderror text-sm py-1" id="contract_next_date" wire:model="contract_next_date">
        @error('contract_next_date') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    {{-- 有効 --}}
    <td style="width: 8rem;" class="text-right"><label for="isvalid">{{ __('Valid') }}</label></td>
    <td style="width: 8rem;">
        <input type="checkbox" class="form-control @error('isvalid') is-invalid @enderror text-sm py-1" id="isvalid" wire:model="isvalid">
        @error('isvalid') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td style="width: 8rem;" class="text-right"><label for="notes">{{ __('Notes') }}</label></td>
    <td colspan="7">
        <input type="text" style="width: 56rem;" class="form-control @error('notes') is-invalid @enderror text-sm py-1" id="notes" wire:model="notes">
        @error('notes') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
</table>

<table class="min-w-full table-auto text-sm">
<tr class="border-b">
    <td class="text-lg">
        契約明細
    </td>
</tr>
</table>

<table class="min-w-full table-auto text-sm">
<tr class="border-b">
    <td style="width: 5rem;"></td>
    <td style="width: 8rem;">表示順／標準単価</td>
    <td colspan="2" class="text-center">サービス／数量 単位</td>
    <td style="width: 8rem;" class="text-center">役割／税区分</td>
    <td colspan="3" class="text-center">明細名／科目 備考</td>
    <td>
    </td>
</tr>
@foreach($details as $key => $value)
<tr class="border-b">
    <td rowspan="2" style="width: 5rem;" class="text-center">{{ ($key + 1) }}</td>
    <td>
        {{-- 表示順 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('details.{{$key}}.cont_dtl_order') is-invalid @enderror text-sm py-1 text-right" id="details.{{$key}}.cont_dtl_order" wire:model="details.{{$key}}.cont_dtl_order" wire:change="updateDetailOrder({{$key}}, $event.target.value)">
        @error('details'.$key.'cont_dtl_order')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="2">
        {{-- サービス --}}
        <select class="form-control @error('details.{{$key}}.service_id') is-invalid @enderror text-sm py-1" id="details.{{$key}}.service_id" wire:model="details.{{$key}}.service_id">
            <option value=""></option>
            @foreach($Services as $Service)
                <option value="{{ $Service->id }}">{{ $Service->svc_name }}</option>
            @endforeach
        </select>
        @error('details'.$key.'service_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 担当者役割 --}}
        <select class="form-control @error('details.{{$key}}.person_role_id') is-invalid @enderror text-sm py-1" id="details.{{$key}}.person_role_id" wire:model="details.{{$key}}.person_role_id">
            @foreach($PersonRoles as $PersonRole)
                <option value="{{ $PersonRole->id }}">{{ $PersonRole->role_name }}</option>
            @endforeach
        </select>
        @error('details'.$key.'person_role_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="3">
        {{-- 明細項目名 --}}
        <input type="text" style="width: 32rem;" class="form-control @error('details.{{$key}}.cont_dtl_title') is-invalid @enderror text-sm py-1" id="details.{{$key}}.cont_dtl_title" wire:model="details.{{$key}}.cont_dtl_title">
        @error('details'.$key.'cont_dtl_title') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
    </td>
</tr>
<tr class="border-b">
    <td style="width: 8rem;">
        {{-- 標準単価 --}}
        <input type="text" style="width: 8rem;" style="width: 8rem;" class="form-control @error('details.{{$key}}.cont_dtl_unit_price') is-invalid @enderror text-sm py-1 text-right" id="details.{{$key}}.cont_dtl_unit_price" wire:model="details.{{$key}}.cont_dtl_unit_price" wire:change="updateDetailUnitPrice({{$key}}, $event.target.value)">
        @error('details'.$key.'cont_dtl_unit_price') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 標準数量 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('details.{{$key}}.cont_dtl_quantity') is-invalid @enderror text-sm py-1 text-right" id="details.{{$key}}.cont_dtl_quantity text-right" wire:model="details.{{$key}}.cont_dtl_quantity" wire:change="updateDetailQuantity({{$key}}, $event.target.value)">
        @error('details'.$key.'cont_dtl_quantity') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 単位 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('details.{{$key}}.cont_dtl_unit') is-invalid @enderror text-sm py-1" id="details.{{$key}}.cont_dtl_unit" wire:model="details.{{$key}}.cont_dtl_unit">
        @error('details'.$key.'cont_dtl_unit') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 税率区分 --}}
        <select class="form-control @error('details.{{$key}}.cont_dtl_tax_type') is-invalid @enderror text-sm py-1" id="details.{{$key}}.cont_dtl_tax_type" wire:model="details.{{$key}}.cont_dtl_tax_type">
            <option value="0">非課税</option>
            <option value="1">課税</option>
            <option value="2">軽減</option>
        </select>
        @error('details'.$key.'cont_dtl_tax_type')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 科目 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('details.{{$key}}.cont_dtl_acc_item') is-invalid @enderror text-sm py-1" id="details.{{$key}}.cont_dtl_acc_item" wire:model="details.{{$key}}.cont_dtl_acc_item">
        @error('details'.$key.'cont_dtl_acc_item') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="2">
        <input type="text" style="width: 20rem;" class="form-control @error('details.{{$key}}.notes') is-invalid @enderror text-sm py-1" id="details.{{$key}}.notes" wire:model="details.{{$key}}.notes">
        @error('details'.$key.'notes') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        <span onclick="deleteDetail({{$key}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">{{ __('Delete') }}</span>
    </td>
</tr>
@endforeach

<tr class="border-b">
    <td rowspan="2" style="width: 5rem;">新規</td>
    <td>
        {{-- 表示順 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newDetail.cont_dtl_order') is-invalid @enderror text-sm py-1 text-right" id="newDetail.cont_dtl_order" wire:model="newDetail.cont_dtl_order" wire:change="updateNewDetailOrder($event.target.value)">
        @error('newDetail.cont_dtl_order')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="2">
        {{-- サービス --}}
        <select class="form-control @error('newDetail.service_id') is-invalid @enderror text-sm py-1" id="newDetail.service_id" wire:model="newDetail.service_id">
            <option value=""></option>
            @foreach($Services as $Service)
                <option value="{{ $Service->id }}">{{ $Service->svc_name }}</option>
            @endforeach
        </select>
        @error('newDetail.service_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 担当者役割 --}}
        <select class="form-control @error('newDetail.person_role_id') is-invalid @enderror text-sm py-1" id="newDetail.person_role_id" wire:model="newDetail.person_role_id">
            @foreach($PersonRoles as $PersonRole)
                <option value="{{ $PersonRole->id }}">{{ $PersonRole->role_name }}</option>
            @endforeach
        </select>
        @error('newDetail.person_role_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="3">
            {{-- 明細項目名 --}}
            <input type="text" style="width: 32rem;" class="form-control @error('newDetail.cont_dtl_title') is-invalid @enderror text-sm py-1" id="newDetail.cont_dtl_title" wire:model="newDetail.cont_dtl_title">
            @error('newDetail.cont_dtl_title') 
                <span class="text-danger">{{ $message }}</span>
            @enderror
    </td>
    <td>
        <span wire:click.prevent="addNewDetail()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">{{ __('Add') }}</span>
    </td>
</tr>
<tr class="border-b">
    <td style="width: 8rem;">
        {{-- 標準単価 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newDetail.cont_dtl_unit_price') is-invalid @enderror text-sm py-1 text-right" id="newDetail.cont_dtl_unit_price" wire:model="newDetail.cont_dtl_unit_price" wire:change="updateNewDetailUnitPrice($event.target.value)">
        @error('newDetail.cont_dtl_unit_price') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 標準数量 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newDetail.cont_dtl_quantity') is-invalid @enderror text-sm py-1 text-right" id="newDetail.cont_dtl_quantity" wire:model="newDetail.cont_dtl_quantity" wire:change="updateNewDetailQuantity($event.target.value)">
        @error('newDetail.cont_dtl_quantity') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 単位 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newDetail.cont_dtl_unit') is-invalid @enderror text-sm py-1" id="newDetail.cont_dtl_unit" wire:model="newDetail.cont_dtl_unit">
        @error('newDetail.cont_dtl_unit') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 税率区分 --}}
        <select class="form-control @error('newDetail.cont_dtl_tax_type') is-invalid @enderror text-sm py-1" id="newDetail.cont_dtl_tax_type" wire:model="newDetail.cont_dtl_tax_type">
            <option value="0">非課税</option>
            <option value="1">課税</option>
            <option value="2">軽減</option>
        </select>
        @error('newDetail.cont_dtl_tax_type') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 科目 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newDetail.cont_dtl_acc_item') is-invalid @enderror text-sm py-1" id="newDetail.cont_dtl_acc_item" wire:model="newDetail.cont_dtl_acc_item">
        @error('newDetail.cont_dtl_acc_item') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="2">
        <input type="text" style="width: 20rem;" class="form-control @error('newDetail.notes') is-invalid @enderror text-sm py-1" id="newDetail.notes" wire:model="newDetail.notes">
        @error('newDetail.notes') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        <span wire:click.prevent="resetNewDetail()" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-1 px-2 rounded">{{ __('Clear') }}</span>
    </td>
</tr>
<script>
    function deleteDetail(key){
        if(confirm('Are you sure to delete this record?')){
            Livewire.dispatch('deleteDetailListener', { key: key });
        }
    }
</script>
