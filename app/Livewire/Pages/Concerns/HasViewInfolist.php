<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Concerns;

use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasViewInfolist
{
    public $record;

    abstract protected static function getModel(): string;

    abstract protected static function getListRouteName(): string;

    abstract protected static function getResourceLabel(): string;

    public function mount(int|string $record): void
    {
        $this->record = $this->getRecordQuery()->findOrFail($record);
    }

    public function infolist(Schema $schema): Schema
    {
        $schema = $schema->record($this->record);

        if ($infolistClass = static::getInfolistSchemaClass()) {
            return $infolistClass::configure($schema);
        }

        if ($formClass = static::getFormSchemaClass()) {
            return $formClass::configure(
                $schema->disabled()->operation('view')
            );
        }

        return $schema;
    }

    protected static function getInfolistSchemaClass(): ?string
    {
        return null;
    }

    protected static function getFormSchemaClass(): ?string
    {
        return null;
    }

    /** @return Builder<Model> */
    protected function getRecordQuery(): Builder
    {
        return static::getModel()::query();
    }
}
