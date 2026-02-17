@include('livewire.pages._partials.view-infolist', [
    'title' => __('Employee Details'),
    'subtitle' => $this->record->first_name . ' ' . $this->record->last_name,
    'backUrl' => route('dashboard.employees'),
    'editUrl' => route('dashboard.employees.edit', $this->record),
])
