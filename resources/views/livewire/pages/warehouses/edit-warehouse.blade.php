@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Warehouse'),
    'subtitle' => $this->record->name,
    'backUrl' => route('dashboard.warehouses'),
    'viewUrl' => route('dashboard.warehouses.view', $this->record),
])
