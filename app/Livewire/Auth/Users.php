<?php

namespace App\Livewire\Auth;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Users extends Component
{
    public function render()
    {
        $userId = auth()->id();

        $users = User::query()
            ->when(auth()->user()->role !== Role::SuperAdmin, function (Builder $query) use ($userId) {
                $query
                    ->where('id', $userId)
                    ->orWhere('agent_id', $userId)
                    ->orWhere('customer_id', $userId);
            })
            ->paginate();

        return view('livewire.auth.users', [
            "title" => "Users",
            "users" => $users
        ]);
    }
}
