@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit CN Code'),
    'subtitle' => $this->record->code,
    'backUrl' => route('dashboard.cn-codes'),
])
