@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Batch'),
    'subtitle' => $this->record->batch_number,
    'backUrl' => route('dashboard.batches'),
])
