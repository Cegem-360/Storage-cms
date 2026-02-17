@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Supplier'),
    'subtitle' => $this->record->company_name,
    'backUrl' => route('dashboard.suppliers'),
    'viewUrl' => route('dashboard.suppliers.view', $this->record),
])
