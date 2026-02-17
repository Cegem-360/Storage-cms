@include('livewire.pages._partials.view-infolist', [
    'title' => __('Warehouse Details'),
    'subtitle' => $this->record->name,
    'backUrl' => route('dashboard.warehouses'),
    'editUrl' => route('dashboard.warehouses.edit', $this->record),
])
