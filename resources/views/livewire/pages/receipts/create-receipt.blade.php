@include('livewire.pages._partials.create-form', [
    'title' => __('Create Receipt'),
    'subtitle' => __('Record a new goods receipt'),
    'backUrl' => route('dashboard.receipts'),
])
