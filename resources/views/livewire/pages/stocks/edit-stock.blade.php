@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Stock'),
    'subtitle' => $this->record->product?->name ?? __('Stock'),
    'backUrl' => route('dashboard.stocks'),
    'viewUrl' => route('dashboard.stocks.view', $this->record),
])
