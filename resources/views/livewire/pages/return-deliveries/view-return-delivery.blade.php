@include('livewire.pages._partials.view-infolist', [
    'title' => __('Return Delivery Details'),
    'subtitle' => $this->record->return_number,
    'backUrl' => route('dashboard.return-deliveries'),
    'editUrl' => route('dashboard.return-deliveries.edit', $this->record),
    'headerActions' => [$this->approveAction, $this->rejectAction, $this->restockAction],
])
