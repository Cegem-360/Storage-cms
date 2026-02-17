@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit User'),
    'subtitle' => $this->record->name,
    'backUrl' => route('dashboard.users'),
    'viewUrl' => route('dashboard.users.view', $this->record),
])
