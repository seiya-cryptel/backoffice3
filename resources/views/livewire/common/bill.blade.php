<tr class="border-b">
    <td colspan="10">
        <div class="col-md-8 mb-2">
            @if(session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session()->get('success') }}
                </div>
            @endif                
            @if(session()->has('error'))
                <div class="alert alert-danger text-red-500" role="alert">
                    {{ session()->get('error') }}
                </div>
            @endif
        </div>
    </td>
</tr>
<tr class="border-b">
    {{-- 請求番号 --}}
    <td style="width: 6rem;" class="text-right"><label for="bill_no">{{ __('Bill No') }}</label><span style="color: red;">*</span></td>
    <td style="width: 8rem;">
        <input type="text" style="width: 8rem;" class="form-control @error('bill_no') is-invalid @enderror text-sm py-1 text-center" id="bill_no" wire:model="bill_no">
        @error('bill_no') 
            <br><span class="text-red-500">{{ $message }}</span>
        @enderror
    </td>
    {{-- 顧客 --}}
    <td style="width: 4rem;" class="text-right"><label for="bill_no">{{ __('Client') }}</label><span style="color: red;">*</span></td>
    <td colspan="2">
        <select class="form-control @error('client_id') is-invalid @enderror text-sm py-1" id="client_id" wire:model="client_id">
            <option value=""></option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}">{{ $client->cl_short_name }}</option>
            @endforeach
        </select>
        @error('client_id')
            <br><span class="text-red-500">{{ $message }}</span>
        @enderror
    </td>
    {{-- 請求件名 --}}
    <td style="width: 8rem;" class="text-right"><label for="bill_title">{{ __('Bill Title') }}</label><span style="color: red;">*</span></td>
    <td colspan="2">
        <input type="text" style="width: 32rem;" class="form-control @error('bill_title') is-invalid @enderror text-sm py-1" id="bill_title" wire:model="bill_title">
        @error('bill_title') 
            <br><span class="text-red-500">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    {{-- 請求日 --}}
    <td style="width: 6rem;" class="text-right"><label for="bill_date">{{ __('Bill Date') }}</label></td>
    <td>
        <input type="date" style="width: 10rem;" class="form-control @error('bill_date') is-invalid @enderror text-sm py-1" id="bill_date" wire:model="bill_date">
        @error('bill_date') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 支払条件 --}}
    <td style="width: 8rem;" class="text-right"><label for="payment_notice">{{ __('Payment Notice') }}</label></td>
    <td>
        <input type="text" style="width: 14rem;" class="form-control @error('payment_notice') is-invalid @enderror text-sm py-1" id="payment_notice" wire:model="payment_notice">
        @error('payment_notice') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 代表者表示フラグ --}}
    <td style="width: 12rem;">
        {{ __('Show CEO') }}
        <input type="checkbox" class="form-control @error('show_ceo') is-invalid @enderror text-sm py-1" id="show_ceo" wire:model="show_ceo">
        @error('show_ceo') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 請求備考 --}}
    <td style="width: 4rem;" class="text-right"><label for="notes">{{ __('Notes') }}</label></td>
    <td colspan="2">
        <input type="text" style="width: 32rem;" class="form-control @error('notes') is-invalid @enderror text-sm py-1" id="notes" wire:model="notes">
        @error('notes') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
</table>

<table class="min-w-full table-auto text-sm">
<tr class="border-b">
</tr>
</table>

<table class="min-w-full table-auto text-sm">
<tr class="border-b">
    <td style="width: 5rem;">請求明細</td>
    <td style="width: 8rem;">表示順／請求単価</td>
    <td colspan="2" class="text-center">サービス／数量 単位</td>
    <td style="width: 8rem;" class="text-center">役割／税区分</td>
    <td colspan="3" class="text-center">明細名／税額 金額 科目 備考</td>
</tr>

@foreach($billDetails as $key => $value)
<tr class="border-b">
    <td rowspan="2" style="width: 5rem;" class="text-center">{{ ($key + 1) }}</td>
    <td>
        {{-- 表示順 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('billDetails.{{$key}}.bill_dtl_order') is-invalid @enderror text-sm py-1 text-right" id="billDetails.{{$key}}.bill_dtl_order" wire:model="billDetails.{{$key}}.bill_dtl_order" wire:change="updateBillDetailOrder({{$key}}, $event.target.value)" placeholder="(表示順)">
        @error('billDetails.'.$key.'.bill_dtl_order')
            <br><span class="text-red-500">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="2">
        {{-- サービス --}}
        <select class="form-control @error('billDetails.{{$key}}.service_id') is-invalid @enderror text-sm py-1" id="billDetails.{{$key}}.service_id" wire:model="billDetails.{{$key}}.service_id">
            <option value=""></option>
            @foreach($services as $service)
                <option value="{{ $service->id }}">{{ $service->svc_name }}</option>
            @endforeach
        </select>
        @error('billDetails.'.$key.'.service_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 担当者役割 --}}
        <select class="form-control @error('billDetails.{{$key}}.person_role_id') is-invalid @enderror text-sm py-1" id="billDetails.{{$key}}.person_role_id" wire:model="billDetails.{{$key}}.person_role_id">
            @foreach($personRoles as $personRole)
                <option value="{{ $personRole->id }}">{{ $personRole->role_name }}</option>
            @endforeach
        </select>
        @error('billDetails.'.$key.'.person_role_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="4">
        {{-- 明細項目名 --}}
        <input type="text" style="width: 32rem;" class="form-control @error('billDetails.{{$key}}.bill_dtl_title') is-invalid @enderror text-sm py-1" id="billDetails.{{$key}}.bill_dtl_title" wire:model="billDetails.{{$key}}.bill_dtl_title">
        @error('billDetails.'.$key.'.bill_dtl_title') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
    </td>
</tr>
<tr class="border-b">
    <td style="width: 8rem;">
        {{-- 請求単価 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('billDetails.{{$key}}.bill_dtl_unit_price') is-invalid @enderror text-sm py-1 text-right" id="billDetails.{{$key}}.bill_dtl_unit_price" wire:model="billDetails.{{$key}}.bill_dtl_unit_price" wire:change="updateBillDetailUnitPrice({{$key}}, $event.target.value)">
        @error('billDetails.'.$key.'.bill_dtl_unit_price') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 数量 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('billDetails.{{$key}}.bill_dtl_quantity') is-invalid @enderror text-sm py-1 text-right" id="billDetails.{{$key}}.bill_dtl_quantity text-right" wire:model="billDetails.{{$key}}.bill_dtl_quantity" wire:change="updateBillDetailQuantity({{$key}}, $event.target.value)">
        @error('billDetails.'.$key.'.bill_dtl_quantity') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 単位 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('billDetails.{{$key}}.bill_dtl_unit') is-invalid @enderror text-sm py-1" id="billDetails.{{$key}}.bill_dtl_unit" wire:model="billDetails.{{$key}}.bill_dtl_unit">
        @error('billDetails.'.$key.'.bill_dtl_unit') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 税率区分 --}}
        <select class="form-control @error('billDetails.{{$key}}.bill_dtl_tax_type') is-invalid @enderror text-sm py-1" id="billDetails.{{$key}}.bill_dtl_tax_type" wire:model="billDetails.{{$key}}.bill_dtl_tax_type" wire:change="updateBillDetailTaxType({{$key}}, $event.target.value)">
            <option value="0">非課税</option>
            <option value="1">課税</option>
            <option value="2">軽減</option>
        </select>
        @error('billDetails.'.$key.'.bill_dtl_tax_type')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 税額 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('billDetails.{{$key}}.bill_dtl_tax') is-invalid @enderror text-sm py-1" id="billDetails.{{$key}}.bill_dtl_tax" wire:model="billDetails.{{$key}}.bill_dtl_tax">
        @error('billDetails.'.$key.'.bill_dtl_tax') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 金額 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('billDetails.{{$key}}.bill_dtl_amount') is-invalid @enderror text-sm py-1" id="billDetails.{{$key}}.bill_dtl_amount" wire:model="billDetails.{{$key}}.bill_dtl_amount">
        @error('billDetails.'.$key.'.bill_dtl_amount') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 科目 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('billDetails.{{$key}}.bill_dtl_acc_item') is-invalid @enderror text-sm py-1" id="billDetails.{{$key}}.bill_dtl_acc_item" wire:model="billDetails.{{$key}}.bill_dtl_acc_item">
        @error('billDetails.'.$key.'.bill_dtl_acc_item') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        <input type="text" style="width: 8rem;" class="form-control @error('billDetails.{{$key}}.notes') is-invalid @enderror text-sm py-1" id="billDetails.{{$key}}.notes" wire:model="billDetails.{{$key}}.notes">
        @error('billDetails'.$key.'notes') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        <span onclick="deleteBillDetail({{$key}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">{{ __('Delete') }}</span>
    </td>
</tr>
@endforeach
<tr>
    <td colspan="10">
    新規明細
    </td>
</tr>
<tr class="border-b">
    <td rowspan="2" style="width: 5rem;" class="text-center"></td>
    <td>
        {{-- 表示順 --}}
        <input type="text" style="width: 4rem;" class="form-control @error('newBillDetail.bill_dtl_order') is-invalid @enderror text-sm py-1 text-right" id="newBillDetail.bill_dtl_order" wire:model="newBillDetail.bill_dtl_order" placeholder="(番号)">
        @error('newBillDetail.bill_dtl_order')
            <br><span class="text-red-500">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="2">
        {{-- サービス --}}
        <select class="form-control @error('newBillDetail.service_id') is-invalid @enderror text-sm py-1" id="newBillDetail.service_id" wire:model="newBillDetail.service_id">
            <option value="">（サービス）</option>
            @foreach($services as $service)
                <option value="{{ $service->id }}">{{ $service->svc_name }}</option>
            @endforeach
        </select>
        @error('newBillDetail.service_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 担当者役割 --}}
        <select class="form-control @error('newBillDetail.person_role_id') is-invalid @enderror text-sm py-1" id="newBillDetail.person_role_id" wire:model="newBillDetail.person_role_id">
            @foreach($personRoles as $personRole)
                <option value="{{ $personRole->id }}">{{ $personRole->role_name }}</option>
            @endforeach
        </select>
        @error('newBillDetail.person_role_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="4">
        {{-- 明細項目名 --}}
        <input type="text" style="width: 32rem;" class="form-control @error('newBillDetail.bill_dtl_title') is-invalid @enderror text-sm py-1" id="newBillDetail.bill_dtl_title" wire:model="newBillDetail.bill_dtl_title" placeholder="(明細名)">
        @error('newBillDetail.bill_dtl_title') 
            <br><span class="text-red-500">{{ $message }}</span>
        @enderror
    </td>
    <td>
        <span wire:click.prevent="addBillDetail()" style="width: 4rem;" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">{{ __('Add') }}</span>
    </td>
</tr>
<tr class="border-b">
    <td style="width: 8rem;">
        {{-- 請求単価 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newBillDetail.bill_dtl_unit_price') is-invalid @enderror text-sm py-1 text-right" id="newBillDetail.bill_dtl_unit_price" wire:model="newBillDetail.bill_dtl_unit_price" wire:change="updateNewBillDetailUnitPrice($event.target.value)" placeholder="(単価)">
        @error('newBillDetail.bill_dtl_unit_price') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 数量 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newBillDetail.bill_dtl_quantity') is-invalid @enderror text-sm py-1 text-right" id="newBillDetail.bill_dtl_quantity" wire:model="newBillDetail.bill_dtl_quantity" wire:change="updateNewBillDetailQuantity($event.target.value)" placeholder="(数量)">
        @error('newBillDetail.bill_dtl_quantity') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 単位 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newBillDetail.bill_dtl_unit') is-invalid @enderror text-sm py-1" id="newBillDetail.bill_dtl_unit" wire:model="newBillDetail.bill_dtl_unit" placeholder="(単位)">
        @error('newBillDetail.bill_dtl_unit') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 税率区分 --}}
        <select class="form-control @error('newBillDetail.bill_dtl_tax_type') is-invalid @enderror text-sm py-1" id="newBillDetail.bill_dtl_tax_type" wire:model="newBillDetail.bill_dtl_tax_type" wire:change="updateNewBillDetailTaxType($event.target.value)">
            <option value="0">非課税</option>
            <option value="1">課税</option>
            <option value="2">軽減</option>
        </select>
        @error('newBillDetail.bill_dtl_tax_type') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 税額 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newBillDetail.bill_dtl_tax') is-invalid @enderror text-sm py-1 text-right" id="newBillDetail.bill_dtl_tax" wire:model="newBillDetail.bill_dtl_tax">
        @error('newBillDetail.bill_dtl_tax') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 金額 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newBillDetail.bill_dtl_amount') is-invalid @enderror text-sm py-1 text-right" id="newBillDetail.bill_dtl_amount" wire:model="newBillDetail.bill_dtl_amount">
        @error('newBillDetail.bill_dtl_amount') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 科目 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newBillDetail.bill_dtl_acc_item') is-invalid @enderror text-sm py-1" id="newBillDetail.bill_dtl_acc_item" wire:model="newBillDetail.bill_dtl_acc_item">
        @error('newBillDetail.bill_dtl_acc_item') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        <input type="text" style="width: 8rem;" class="form-control @error('newBillDetail.notes') is-invalid @enderror text-sm py-1" id="newBillDetail.notes" wire:model="newBillDetail.notes">
        @error('newBillDetail.notes') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        <span wire:click.prevent="resetBillDetail()" style="width: 4rem;" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-1 px-2 rounded">{{ __('Clear') }}</span>
    </td>
    </tr>
    <script>
        function deleteBillDetail(key){
            if(confirm('Are you sure to delete this record?')){
                Livewire.dispatch('deleteBillDetailListener', { key: key });
            }
        }
    </script>
