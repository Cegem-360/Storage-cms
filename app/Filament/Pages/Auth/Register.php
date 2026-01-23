<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class Register extends BaseRegister
{
    public string $view = 'filament.pages.auth.register';

    protected static string $layout = 'filament.layouts.auth-split';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->columns(1);
    }

    protected function getNameFormComponent(): TextInput
    {
        return TextInput::make('name')
            ->label(__('Full name'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getEmailFormComponent(): TextInput
    {
        return TextInput::make('email')
            ->label(__('Work email'))
            ->email()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }

    protected function getPasswordFormComponent(): TextInput
    {
        return TextInput::make('password')
            ->label(__('Password'))
            ->password()
            ->revealable()
            ->required()
            ->minLength(8)
            ->same('passwordConfirmation')
            ->validationAttribute(__('password'));
    }

    protected function getPasswordConfirmationFormComponent(): TextInput
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('Confirm password'))
            ->password()
            ->revealable()
            ->required()
            ->dehydrated(false);
    }
}
