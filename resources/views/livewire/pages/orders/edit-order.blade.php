@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Order'),
    'subtitle' => $this->record->order_number,
    'backUrl' => route('dashboard.orders'),
    'viewUrl' => route('dashboard.orders.view', $this->record),
])
