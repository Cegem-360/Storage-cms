@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Customer'),
    'subtitle' => $this->record->name,
    'backUrl' => route('dashboard.customers'),
    'viewUrl' => route('dashboard.customers.view', $this->record),
])
