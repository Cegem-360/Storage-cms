@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Employee'),
    'subtitle' => $this->record->first_name . ' ' . $this->record->last_name,
    'backUrl' => route('dashboard.employees'),
    'viewUrl' => route('dashboard.employees.view', $this->record),
])
