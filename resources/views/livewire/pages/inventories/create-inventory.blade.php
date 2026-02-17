@include('livewire.pages._partials.create-form', [
    'title' => __('Create Inventory'),
    'subtitle' => __('Start a new inventory count'),
    'backUrl' => route('dashboard.inventories'),
])
