<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Concerns;

use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

trait HasCreateForm
{
    /** @var array<string, mixed>|null */
    public ?array $data = [];

    abstract protected static function getModel(): string;

    abstract protected static function getFormSchemaClass(): string;

    abstract protected static function getListRouteName(): string;

    abstract protected static function getEditRouteName(): string;

    abstract protected static function getResourceLabel(): string;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        $schemaClass = static::getFormSchemaClass();

        return $schemaClass::configure(
            $schema
                ->statePath('data')
                ->model(static::getModel())
                ->operation('create')
        );
    }

    public function create(): void
    {
        $data = $this->form->getState();
        $data = $this->mutateFormDataBeforeCreate($data);

        $modelClass = static::getModel();
        $record = $modelClass::create($data);

        $this->form->model($record)->saveRelationships();

        $this->afterCreate($record);

        Notification::make()
            ->title(__(static::getResourceLabel().' created'))
            ->success()
            ->send();

        $this->redirect(route(static::getEditRouteName(), $record), navigate: true);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function afterCreate(Model $record): void {}
}
