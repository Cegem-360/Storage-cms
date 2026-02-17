@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Intrastat Inbound'),
    'subtitle' => $this->record->reference_number,
    'backUrl' => route('dashboard.intrastat-inbounds'),
])
