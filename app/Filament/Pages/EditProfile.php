<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class EditProfile extends BaseEditProfile
{
    protected string $view = 'filament.pages.edit-profile';

    public function getHeading(): string
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile Information')
                    ->description(__('Update your account profile information.'))
                    ->components([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                    ])
                    ->columns(2),

                Section::make('Update Password')
                    ->description(__('Ensure your account is using a long, random password to stay secure.'))
                    ->components([
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getCurrentPasswordFormComponent(),
                    ]),
            ])
            ->statePath('data');
    }
}
