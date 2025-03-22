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
            <button wire:click="createEstimate()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold text-sm py-1 px-2 rounded">{{ __('Add') }}</button>
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
                        <th>{{ __('Estimate No') }}</th>
                        <th>{{ __('Client Name') }}</th>
                        <th>{{ __('Estimate Title') }}</th>
                        <th>{{ __('Estimated Date') }}</th>
                        <th>{{ __('Estimate Amount') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @if (count($Estimates) > 0)
                @foreach ($Estimates as $Estimate)
                    <tr class="border-b">
                        <td>
                            {{$Estimate->estimate_no}}
                        </td>
                        <td>
                            {{$Estimate->client->cl_short_name}}
                        </td>
                        <td>
                            {{$Estimate->estimate_title}}
                        </td>
                        <td>
                            {{$Estimate->estimate_date}}
                        </td>
                        <td class="px-1 text-right">
                            {{number_format($Estimate->estimateDetails->sum('estm_dtl_amount'))}}
                        </td>
                        <td>
                        <button wire:click="updateEstimate({{$Estimate->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">{{ __('Edit') }}</button>
                        <button wire:click="copyEstimate({{$Estimate->id}})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">{{ __('Copy') }}</button>
                        <button wire:click="generateBill({{$Estimate->id}})" class="bg-yellow-700 hover:bg-green-900 text-white font-bold py-1 px-2 rounded">{{ __('Bill') }}</button>
                        <button wire:click="downloadEstimatePdf({{$Estimate->id}})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">{{ __('PDF') }}</button>
                        <button wire:click="downloadEstimateExcel({{$Estimate->id}})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">{{ __('Excel') }}</button>
                        <button onclick="deleteEstimate({{$Estimate->id}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">{{ __('Delete') }}</button>
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
        {{ $Estimates->links() }}
    </div>    
    <script>
        function deleteEstimate(id){
            if(confirm('Are you sure to delete this record?')){
                Livewire.dispatch('deleteEstimateListener', { id: id });
            }
        }
    </script>
</div>
