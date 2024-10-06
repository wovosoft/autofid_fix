<?php

namespace App\Models;

use App\Enums\Role;
use App\Traits\HasIdentities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property string $user_role
 * @property int|null $agent_id
 * @property int|null $customer_id
 * @property int $user_id
 * @property string $title
 * @property string $appointment_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\AppointmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereAppointmentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereUserRole($value)
 * @property-read \App\Models\User|null $agent
 * @property-read \App\Models\User|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Slip> $slips
 * @property-read int|null $slips_count
 * @property-read \App\Models\User|null $user
 * @mixin \Eloquent
 */
class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;
    use HasIdentities;

    protected function casts(): array
    {
        return [
            'user_role' => Role::class,
        ];
    }

    public function slips(): HasMany
    {
        return $this->hasMany(Slip::class, 'appointment_id');
    }
}
