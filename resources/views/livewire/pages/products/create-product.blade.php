@include('livewire.pages._partials.create-form', [
    'title' => __('Create Product'),
    'subtitle' => __('Add a new product to your catalog'),
    'backUrl' => route('dashboard.products'),
])
