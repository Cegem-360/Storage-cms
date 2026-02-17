@include('livewire.pages._partials.view-infolist', [
    'title' => __('User Details'),
    'subtitle' => $this->record->name,
    'backUrl' => route('dashboard.users'),
    'editUrl' => route('dashboard.users.edit', $this->record),
])
