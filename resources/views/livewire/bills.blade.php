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
            <button wire:click="createBill()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold text-sm py-1 px-2 rounded">{{ __('Add') }}</button>
        </div>
        <!-- 検索窓の追加 -->
        <div>
            <input wire:model.live="search" type="text" class="form-control text-sm py-1" id="search" wire:change="changeSearch($event.target.value)">
            <span>
                <button wire:click="clearSearch()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">X</button>
            </span>
        </div>
        <div>
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th>{{ __('Bill No') }}</th>
                        <th>{{ __('Client Name') }}</th>
                        <th>{{ __('Bill Title') }}</th>
                        <th>{{ __('Bill Date') }}</th>
                        <th>{{ __('Bill Amount') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @if (count($Bills) > 0)
                @foreach ($Bills as $Bill)
                    <tr class="border-b">
                        <td>
                            {{$Bill->bill_no}}
                        </td>
                        <td>
                            {{$Bill->client->cl_short_name}}
                        </td>
                        <td>
                            {{$Bill->bill_title}}
                        </td>
                        <td>
                            {{$Bill->bill_date}}
                        </td>
                        <td class="px-1 text-right">
                            {{number_format($Bill->billDetails->sum('bill_dtl_amount'))}}
                        </td>
                        <td>
                        <button wire:click="updateBill({{$Bill->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">{{ __('Edit') }}</button>
                        <button wire:click="copyBill({{$Bill->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">{{ __('Copy') }}</button>
                        <button wire:click="downloadBillPdf({{$Bill->id}})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">{{ __('PDF') }}</button>
                        <button wire:click="downloadBillExcel({{$Bill->id}})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">{{ __('Excel') }}</button>
                        <button wire:click="downloadReceiptPdf({{$Bill->id}})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">{{ __('Receipt') }}</button>
                        <button onclick="deleteBill({{$Bill->id}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">{{ __('Delete') }}</button>
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
        {{ $Bills->links() }}
    </div>    
    <script>
        function deleteBill(id){
            if(confirm('Are you sure to delete this record?')){
                Livewire.dispatch('deleteBillListener', { id: id });
            }
        }
    </script>
</div>
