<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Concerns;

use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasEditForm
{
    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public $record;

    abstract protected static function getModel(): string;

    abstract protected static function getFormSchemaClass(): string;

    abstract protected static function getListRouteName(): string;

    abstract protected static function getResourceLabel(): string;

    public function mount(int|string $record): void
    {
        $this->record = $this->getRecordQuery()->findOrFail($record);
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        $schemaClass = static::getFormSchemaClass();

        return $schemaClass::configure(
            $schema
                ->statePath('data')
                ->model($this->record)
                ->operation('edit')
        );
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data = $this->mutateFormDataBeforeSave($data);

        $this->record->update($data);
        $this->form->saveRelationships();

        $this->afterSave($this->record);

        Notification::make()
            ->title(__(static::getResourceLabel().' updated'))
            ->success()
            ->send();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function afterSave(Model $record): void {}

    /** @return Builder<Model> */
    protected function getRecordQuery(): Builder
    {
        return static::getModel()::query();
    }
}
