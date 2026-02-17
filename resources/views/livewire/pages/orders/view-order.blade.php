@include('livewire.pages._partials.view-infolist', [
    'title' => __('Order Details'),
    'subtitle' => $this->record->order_number,
    'backUrl' => route('dashboard.orders'),
    'editUrl' => route('dashboard.orders.edit', $this->record),
])
