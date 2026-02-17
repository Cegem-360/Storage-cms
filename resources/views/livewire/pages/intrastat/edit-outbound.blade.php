@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Intrastat Outbound'),
    'subtitle' => $this->record->reference_number,
    'backUrl' => route('dashboard.intrastat-outbounds'),
])
