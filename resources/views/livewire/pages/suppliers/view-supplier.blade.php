@include('livewire.pages._partials.view-infolist', [
    'title' => __('Supplier Details'),
    'subtitle' => $this->record->company_name,
    'backUrl' => route('dashboard.suppliers'),
    'editUrl' => route('dashboard.suppliers.edit', $this->record),
])
