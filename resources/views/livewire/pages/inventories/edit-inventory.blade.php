@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Inventory'),
    'subtitle' => $this->record->inventory_number,
    'backUrl' => route('dashboard.inventories'),
    'viewUrl' => null,
    'headerActions' => [$this->applyCorrectionsAction, $this->exportPdfAction],
])
