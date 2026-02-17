<div>
    @include('livewire.pages._partials._page-header', [
        'title' => __('Create Return Delivery'),
        'subtitle' => __('Record a new return delivery'),
        'backUrl' => route('dashboard.return-deliveries'),
    ])

    <form wire:submit="create">
        {{ $this->form }}
    </form>

    <x-filament-actions::modals />
</div>
