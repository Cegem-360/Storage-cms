<?php

declare(strict_types=1);

namespace App\Livewire\Page;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
#[Title('My Profile')]
final class ProfilePage extends Component
{
    public string $name = '';

    public string $email = '';

    public string $currentPassword = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.Auth::id()],
        ]);

        Auth::user()->update($validated);

        session()->flash('profile_success', __('Profile information updated successfully.'));
    }

    public function updatePassword(): void
    {
        $this->validate([
            'currentPassword' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed:passwordConfirmation'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['currentPassword', 'password', 'passwordConfirmation']);

        session()->flash('password_success', __('Password updated successfully.'));
    }

    public function render(): Factory|View
    {
        return view('livewire.page.profile-page');
    }
}
