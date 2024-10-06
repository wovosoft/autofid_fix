<?php

namespace App\Livewire\Appointments;

use App\Enums\Role;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("layouts.app")]
class Index extends Component
{
    public function render()
    {
        //set this value depending on your needs
        $userId = auth()->id();

        $appointments = Appointment::query()
            ->with([
                'agent:id,name',
                'customer:id,name',
                'user:id,name',
            ])
            ->when(auth()->user()->role !== Role::SuperAdmin, function (Builder $query) use ($userId) {
                $query
                    ->where('user_id', $userId)
                    ->orWhere('agent_id', $userId)
                    ->orWhere('customer_id', $userId);
            })
            ->paginate();
        return view('livewire.appointments.index', [
            "title" => "Appointments",
            "appointments" => $appointments
        ]);
    }
}
