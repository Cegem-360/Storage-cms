@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Receipt'),
    'subtitle' => $this->record->receipt_number,
    'backUrl' => route('dashboard.receipts'),
    'viewUrl' => route('dashboard.receipts.view', $this->record),
])
