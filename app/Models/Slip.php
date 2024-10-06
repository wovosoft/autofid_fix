<?php

namespace App\Models;

use App\Traits\HasIdentities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $appointment_id
 * @property string $user_role
 * @property int|null $agent_id
 * @property int|null $customer_id
 * @property int $user_id
 * @property string $title
 * @property string $appointment_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\SlipFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Slip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slip query()
 * @method static \Illuminate\Database\Eloquent\Builder|Slip whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slip whereAppointmentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slip whereAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slip whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slip whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slip whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slip whereUserRole($value)
 * @property-read \App\Models\User|null $agent
 * @property-read \App\Models\Appointment $appointment
 * @property-read \App\Models\User|null $customer
 * @property-read \App\Models\User|null $user
 * @mixin \Eloquent
 */
class Slip extends Model
{
    /** @use HasFactory<\Database\Factories\SlipFactory> */
    use HasFactory;
    use HasIdentities;

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
