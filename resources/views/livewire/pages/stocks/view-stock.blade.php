@include('livewire.pages._partials.view-infolist', [
    'title' => __('Stock Details'),
    'subtitle' => $this->record->product?->name ?? __('Stock'),
    'backUrl' => route('dashboard.stocks'),
    'editUrl' => route('dashboard.stocks.edit', $this->record),
])
