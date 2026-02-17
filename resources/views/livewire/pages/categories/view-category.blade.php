@include('livewire.pages._partials.view-infolist', [
    'title' => __('Category Details'),
    'subtitle' => $this->record->name,
    'backUrl' => route('dashboard.categories'),
    'editUrl' => route('dashboard.categories.edit', $this->record),
])
