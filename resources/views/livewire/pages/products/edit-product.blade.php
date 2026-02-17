@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Product'),
    'subtitle' => $this->record->name,
    'backUrl' => route('dashboard.products'),
    'viewUrl' => route('dashboard.products.view', $this->record),
])
