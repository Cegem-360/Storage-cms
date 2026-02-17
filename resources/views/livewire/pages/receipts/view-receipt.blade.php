@include('livewire.pages._partials.view-infolist', [
    'title' => __('Receipt Details'),
    'subtitle' => $this->record->receipt_number,
    'backUrl' => route('dashboard.receipts'),
    'editUrl' => route('dashboard.receipts.edit', $this->record),
])
