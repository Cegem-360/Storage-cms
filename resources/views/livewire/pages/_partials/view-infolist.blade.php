<div>
    @include('livewire.pages._partials._page-header', [
        'title' => $title,
        'subtitle' => $subtitle ?? null,
        'backUrl' => $backUrl,
        'editUrl' => $editUrl ?? null,
        'headerActions' => $headerActions ?? null,
    ])

    {{ $this->infolist }}

    <x-filament-actions::modals />
</div>
