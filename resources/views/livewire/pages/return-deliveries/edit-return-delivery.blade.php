@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Return Delivery'),
    'subtitle' => $this->record->return_number,
    'backUrl' => route('dashboard.return-deliveries'),
    'viewUrl' => route('dashboard.return-deliveries.view', $this->record),
])
