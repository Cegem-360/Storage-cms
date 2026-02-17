<?php

declare(strict_types=1);

namespace App\Filament\Resources\IntrastatDeclarations\Pages;

use App\Filament\Resources\IntrastatDeclarations\IntrastatDeclarationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditIntrastatDeclaration extends EditRecord
{
    protected static string $resource = IntrastatDeclarationResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
