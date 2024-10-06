<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Users extends Component
{
    public function render()
    {
        $users = User::query()
            ->paginate();

        return view('livewire.auth.users', [
            "title" => "Users",
            "users" => $users
        ]);
    }
}
