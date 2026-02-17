@include('livewire.pages._partials.view-infolist', [
    'title' => __('Customer Details'),
    'subtitle' => $this->record->name,
    'backUrl' => route('dashboard.customers'),
    'editUrl' => route('dashboard.customers.edit', $this->record),
])
