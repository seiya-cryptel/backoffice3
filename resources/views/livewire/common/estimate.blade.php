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
    {{-- 見積番号 --}}
    <td style="width: 6rem;" class="text-right"><label for="estimate_no">{{ __('Estimate No') }}</label><span style="color: red;">*</span></td>
    <td style="width: 8rem;">
        <input type="text" style="width: 8rem;" class="form-control @error('estimate_no') is-invalid @enderror text-sm py-1 text-center" id="estimate_no" wire:model="estimate_no">
        @error('estimate_no') 
            <br><span class="text-red-500">{{ $message }}</span>
        @enderror
    </td>
    {{-- 顧客 --}}
    <td style="width: 4rem;" class="text-right"><label for="estimate_no">{{ __('Client') }}</label><span style="color: red;">*</span></td>
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
    {{-- 見積件名 --}}
    <td style="width: 8rem;" class="text-right"><label for="estimate_title">{{ __('Estimate Title') }}</label><span style="color: red;">*</span></td>
    <td colspan="4">
        <input type="text" style="width: 36rem;" class="form-control @error('estimate_title') is-invalid @enderror text-sm py-1" id="estimate_title" wire:model="estimate_title">
        @error('estimate_title') 
            <br><span class="text-red-500">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    {{-- 見積日 --}}
    <td style="width: 6rem;" class="text-right"><label for="estimate_date">{{ __('Estimated Date') }}</label></td>
    <td>
        <input type="date" style="width: 10rem;" class="form-control @error('estimate_date') is-invalid @enderror text-sm py-1" id="estimate_date" wire:model="estimate_date">
        @error('estimate_date') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 納品日 --}}
    <td style="width: 4rem;" class="text-right"><label for="deliverly_date">{{ __('Deliverly Date') }}</label></td>
    <td colspan="2">
        <input type="text" style="width: 14rem;" class="form-control @error('deliverly_date') is-invalid @enderror text-sm py-1" id="deliverly_date" wire:model="deliverly_date">
        @error('deliverly_date') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 納品場所 --}}
    <td style="width: 8rem;" class="text-right"><label for="deliverly_place">{{ __('Deliverly Place') }}</label></td>
    <td colspan="2">
        <input type="text" style="width: 14rem;" class="form-control @error('deliverly_place') is-invalid @enderror text-sm py-1" id="deliverly_place" wire:model="deliverly_place">
        @error('deliverly_place') 
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
</tr>
<tr class="border-b">
    {{-- 見積有効期限 --}}
    <td style="width: 8rem;" class="text-right"><label for="valid_until">{{ __('Valid Until') }}</label></td>
    <td>
        <input type="text" style="width: 8rem;" class="form-control @error('valid_until') is-invalid @enderror text-sm py-1" id="valid_until" wire:model="valid_until">
        @error('valid_until') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    {{-- 代表者表示フラグ --}}
    <td></td>
    <td>
        {{ __('Show CEO') }}
        <input type="checkbox" class="form-control @error('show_ceo') is-invalid @enderror text-sm py-1" id="show_ceo" wire:model="show_ceo">
        @error('show_ceo') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td></td>
    {{-- 見積備考 --}}
    <td style="width: 4rem;" class="text-right"><label for="notes">{{ __('Notes') }}</label></td>
    <td colspan="4">
        <input type="text" style="width: 36rem;" class="form-control @error('notes') is-invalid @enderror text-sm py-1" id="notes" wire:model="notes">
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
    <td style="width: 5rem;">見積明細</td>
    <td style="width: 8rem;">表示順／見積単価</td>
    <td colspan="2" class="text-center">サービス／数量 単位</td>
    <td style="width: 8rem;" class="text-center">役割／税区分</td>
    <td colspan="3" class="text-center">明細名／税額 金額 科目 備考</td>
</tr>

@foreach($estimateDetails as $key => $value)
<tr class="border-b">
    <td rowspan="2" style="width: 5rem;" class="text-center">{{ ($key + 1) }}</td>
    <td>
        {{-- 表示順 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('estimateDetails.{{$key}}.estm_dtl_order') is-invalid @enderror text-sm py-1 text-right" id="estimateDetails.{{$key}}.estm_dtl_order" wire:model="estimateDetails.{{$key}}.estm_dtl_order" wire:change="updateEstimateDetailOrder({{$key}}, $event.target.value)" placeholder="(表示順)">
        @error('estimateDetails.'.$key.'.estm_dtl_order')
            <br><span class="text-red-500">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="2">
        {{-- サービス --}}
        <select class="form-control @error('estimateDetails.{{$key}}.service_id') is-invalid @enderror text-sm py-1" id="estimateDetails.{{$key}}.service_id" wire:model="estimateDetails.{{$key}}.service_id">
            <option value=""></option>
            @foreach($services as $service)
                <option value="{{ $service->id }}">{{ $service->svc_name }}</option>
            @endforeach
        </select>
        @error('estimateDetails.'.$key.'.service_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 担当者役割 --}}
        <select class="form-control @error('estimateDetails.{{$key}}.person_role_id') is-invalid @enderror text-sm py-1" id="estimateDetails.{{$key}}.person_role_id" wire:model="estimateDetails.{{$key}}.person_role_id">
            @foreach($personRoles as $personRole)
                <option value="{{ $personRole->id }}">{{ $personRole->role_name }}</option>
            @endforeach
        </select>
        @error('estimateDetails.'.$key.'.person_role_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="4">
        {{-- 明細項目名 --}}
        <input type="text" style="width: 20rem;" class="form-control @error('estimateDetails.{{$key}}.estm_dtl_title') is-invalid @enderror text-sm py-1" id="estimateDetails.{{$key}}.estm_dtl_title" wire:model="estimateDetails.{{$key}}.estm_dtl_title">
        @error('estimateDetails.'.$key.'.estm_dtl_title') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
</tr>
<tr class="border-b">
    <td style="width: 8rem;">
        {{-- 見積単価 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('estimateDetails.{{$key}}.estm_dtl_unit_price') is-invalid @enderror text-sm py-1 text-right" id="estimateDetails.{{$key}}.estm_dtl_unit_price" wire:model="estimateDetails.{{$key}}.estm_dtl_unit_price" wire:change="updateEstimateDetailUnitPrice({{$key}}, $event.target.value)">
        @error('estimateDetails.'.$key.'.estm_dtl_unit_price') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 数量 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('estimateDetails.{{$key}}.estm_dtl_quantity') is-invalid @enderror text-sm py-1 text-right" id="estimateDetails.{{$key}}.estm_dtl_quantity text-right" wire:model="estimateDetails.{{$key}}.estm_dtl_quantity" wire:change="updateEstimateDetailQuantity({{$key}}, $event.target.value)">
        @error('estimateDetails.'.$key.'.estm_dtl_quantity') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 単位 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('estimateDetails.{{$key}}.estm_dtl_unit') is-invalid @enderror text-sm py-1" id="estimateDetails.{{$key}}.estm_dtl_unit" wire:model="estimateDetails.{{$key}}.estm_dtl_unit">
        @error('estimateDetails.'.$key.'.estm_dtl_unit') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 税率区分 --}}
        <select class="form-control @error('estimateDetails.{{$key}}.estm_dtl_tax_type') is-invalid @enderror text-sm py-1" id="estimateDetails.{{$key}}.estm_dtl_tax_type" wire:model="estimateDetails.{{$key}}.estm_dtl_tax_type" wire:change="updateEstimateDetailTaxType({{$key}}, $event.target.value)">
            <option value="0">非課税</option>
            <option value="1">課税</option>
            <option value="2">軽減</option>
        </select>
        @error('estimateDetails.'.$key.'.estm_dtl_tax_type')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 税額 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('estimateDetails.{{$key}}.estm_dtl_tax') is-invalid @enderror text-sm py-1" id="estimateDetails.{{$key}}.estm_dtl_tax" wire:model="estimateDetails.{{$key}}.estm_dtl_tax">
        @error('estimateDetails.'.$key.'.estm_dtl_tax') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 金額 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('estimateDetails.{{$key}}.estm_dtl_amount') is-invalid @enderror text-sm py-1" id="estimateDetails.{{$key}}.estm_dtl_amount" wire:model="estimateDetails.{{$key}}.estm_dtl_amount">
        @error('estimateDetails.'.$key.'.estm_dtl_amount') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 科目 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('estimateDetails.{{$key}}.estm_dtl_acc_item') is-invalid @enderror text-sm py-1" id="estimateDetails.{{$key}}.estm_dtl_acc_item" wire:model="estimateDetails.{{$key}}.estm_dtl_acc_item">
        @error('estimateDetails.'.$key.'.estm_dtl_acc_item') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        <input type="text" style="width: 8rem;" class="form-control @error('estimateDetails.{{$key}}.notes') is-invalid @enderror text-sm py-1" id="estimateDetails.{{$key}}.notes" wire:model="estimateDetails.{{$key}}.notes">
        @error('estimateDetails'.$key.'notes') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        <span onclick="deleteEstimateDetail({{$key}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">{{ __('Delete') }}</span>
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
        <input type="text" style="width: 4rem;" class="form-control @error('newEstimateDetail.estm_dtl_order') is-invalid @enderror text-sm py-1 text-right" id="newEstimateDetail.estm_dtl_order" wire:model="newEstimateDetail.estm_dtl_order" placeholder="(番号)">
        @error('newEstimateDetail.estm_dtl_order')
            <br><span class="text-red-500">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="2">
        {{-- サービス --}}
        <select class="form-control @error('newEstimateDetail.service_id') is-invalid @enderror text-sm py-1" id="newEstimateDetail.service_id" wire:model="newEstimateDetail.service_id">
            <option value="">（サービス）</option>
            @foreach($services as $service)
                <option value="{{ $service->id }}">{{ $service->svc_name }}</option>
            @endforeach
        </select>
        @error('newEstimateDetail.service_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 担当者役割 --}}
        <select class="form-control @error('newEstimateDetail.person_role_id') is-invalid @enderror text-sm py-1" id="newEstimateDetail.person_role_id" wire:model="newEstimateDetail.person_role_id">
            @foreach($personRoles as $personRole)
                <option value="{{ $personRole->id }}">{{ $personRole->role_name }}</option>
            @endforeach
        </select>
        @error('newEstimateDetail.person_role_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td colspan="4">
        {{-- 明細項目名 --}}
        <input type="text" style="width: 32rem;" class="form-control @error('newEstimateDetail.estm_dtl_title') is-invalid @enderror text-sm py-1" id="newEstimateDetail.estm_dtl_title" wire:model="newEstimateDetail.estm_dtl_title" placeholder="(明細名)">
        @error('newEstimateDetail.estm_dtl_title') 
            <br><span class="text-red-500">{{ $message }}</span>
        @enderror
    </td>
    <td>
        <span onclick="addEstimateDetail()" style="width: 4rem;" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">{{ __('Add') }}</span>
    </td>
</tr>
<tr class="border-b">
    <td style="width: 8rem;">
        {{-- 見積単価 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newEstimateDetail.estm_dtl_unit_price') is-invalid @enderror text-sm py-1 text-right" id="newEstimateDetail.estm_dtl_unit_price" wire:model="newEstimateDetail.estm_dtl_unit_price" wire:change="updateNewEstimateDetailUnitPrice($event.target.value)" placeholder="(単価)">
        @error('newEstimateDetail.estm_dtl_unit_price') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 数量 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newEstimateDetail.estm_dtl_quantity') is-invalid @enderror text-sm py-1 text-right" id="newEstimateDetail.estm_dtl_quantity" wire:model="newEstimateDetail.estm_dtl_quantity" wire:change="updateNewEstimateDetailQuantity($event.target.value)" placeholder="(数量)">
        @error('newEstimateDetail.estm_dtl_quantity') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 単位 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newEstimateDetail.estm_dtl_unit') is-invalid @enderror text-sm py-1" id="newEstimateDetail.estm_dtl_unit" wire:model="newEstimateDetail.estm_dtl_unit" placeholder="(単位)">
        @error('newEstimateDetail.estm_dtl_unit') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 税率区分 --}}
        <select class="form-control @error('newEstimateDetail.estm_dtl_tax_type') is-invalid @enderror text-sm py-1" id="newEstimateDetail.estm_dtl_tax_type" wire:model="newEstimateDetail.estm_dtl_tax_type" wire:change="updateNewEstimateDetailTaxType($event.target.value)">
            <option value="0">非課税</option>
            <option value="1">課税</option>
            <option value="2">軽減</option>
        </select>
        @error('newEstimateDetail.estm_dtl_tax_type') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 税額 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newEstimateDetail.estm_dtl_tax') is-invalid @enderror text-sm py-1 text-right" id="newEstimateDetail.estm_dtl_tax" wire:model="newEstimateDetail.estm_dtl_tax">
        @error('newEstimateDetail.estm_dtl_tax') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 金額 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newEstimateDetail.estm_dtl_amount') is-invalid @enderror text-sm py-1 text-right" id="newEstimateDetail.estm_dtl_amount" wire:model="newEstimateDetail.estm_dtl_amount">
        @error('newEstimateDetail.estm_dtl_amount') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        {{-- 科目 --}}
        <input type="text" style="width: 8rem;" class="form-control @error('newEstimateDetail.estm_dtl_acc_item') is-invalid @enderror text-sm py-1" id="newEstimateDetail.estm_dtl_acc_item" wire:model="newEstimateDetail.estm_dtl_acc_item">
        @error('newEstimateDetail.estm_dtl_acc_item') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        <input type="text" style="width: 8rem;" class="form-control @error('newEstimateDetail.notes') is-invalid @enderror text-sm py-1" id="newEstimateDetail.notes" wire:model="newEstimateDetail.notes">
        @error('newEstimateDetail.notes') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </td>
    <td>
        <span onclick="resetEstimateDetail()" style="width: 4rem;" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-1 px-2 rounded">{{ __('Clear') }}</span>
    </td>
    </tr>
    <script>
        function deleteEstimateDetail(key){
            if(confirm('Are you sure to delete this record?')){
                Livewire.dispatch('deleteEstimateDetailListener', { key: key });
            }
        }
        function addEstimateDetail(){
            Livewire.dispatch('addEstimateDetailListener');
        }
        function resetEstimateDetail(){
            Livewire.dispatch('resetEstimateDetailListener');
        }
    </script>
