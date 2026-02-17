@include('livewire.pages._partials.view-infolist', [
    'title' => __('Product Details'),
    'subtitle' => $this->record->name,
    'backUrl' => route('dashboard.products'),
    'editUrl' => route('dashboard.products.edit', $this->record),
    'headerActions' => $this->generateBarcodeAction . $this->printLabelAction,
])
