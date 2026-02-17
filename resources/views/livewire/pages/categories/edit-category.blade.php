@include('livewire.pages._partials.edit-form', [
    'title' => __('Edit Category'),
    'subtitle' => $this->record->name,
    'backUrl' => route('dashboard.categories'),
    'viewUrl' => route('dashboard.categories.view', $this->record),
])
