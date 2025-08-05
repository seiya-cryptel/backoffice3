<x-app-layout>
    <x-slot name="header">
        <h3 class="{{-- font-semibold text-xl --}} text-gray-800 leading-tight text-sm">
        {{ __('Master') }} > {{ __('Client') }}{{ __('Contract') }} > {{ __('Edit') }}
        </h3>
    </x-slot>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container">
                <div class="row justify-content-center mt-3">
                    @livewire('contractEditUpdate', ['client_id' => $client_id, 'id' => $id])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
