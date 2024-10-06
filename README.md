```shell
# Clone 
git clone https://github.com/wovosoft/autofid_fix.git
cd autofid_fix

# Install Dependencies
composer install

# Configure
cp .env.example .env
php artisan key:generate

# Run Migrations, configure database
php artisan migrate

# Initialize Test Cases
php artisan app:initialize-test-bench
```

Check Methods in InitializeTestBench.php

This way, users (Agents, Customers, Staffs, Appointments and Slips) should be created.

## User Creation Process

```php
$user = auth()->user();

$user = new \App\Models\User();
$user->forceFill([
    'agent_id' =>$user->agent_id,
    'customer_id' => $user->customer_id,
    'role' => \App\Enums\Role::values() //any,
    // Other Fields..... 
]);
$user->saveOrFail();
```

## Appointment Creation Process

```php
$user = auth()->user();

$appointment = new \App\Models\Appointment();
$appointment->forceFill([
    'agent_id' =>$user->agent_id,
    'customer_id' => $user->customer_id,
    'user_id' => $user->id,
    'user_role' => $user->role,
    // Other Fields..... 
]);
$appointment->saveOrFail();
```

## Slips Creation Process

```php
$user = auth()->user();
$appointment = \App\Models\Appointment::query()->first();


$slip = new \App\Models\Slip();
$slip->forceFill([
    'agent_id' =>$user->agent_id,
    'customer_id' => $user->customer_id,
    'user_id' => $user->id,
    'user_role' => $user->role,
    'appointment_id' => $appointment->id,
    // Other Fields..... 
]);
$appointment->saveOrFail();
```


## Users Query

```php
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
```


## Appointment Query

```php
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
```


## Slips Query
```php
//set this value depending on your needs
$userId = auth()->id();


$slips = Slip::query()
    ->with([
        'agent:id,name',
        'customer:id,name',
        'user:id,name',
        'appointment'
    ])
    ->when(auth()->user()->role !== Role::SuperAdmin, function (Builder $query) use ($userId) {
        $query
            ->where('user_id', $userId)
            ->orWhere('agent_id', $userId)
            ->orWhere('customer_id', $userId);
    })
    ->paginate();

return view('livewire.appointments.slips', [
    'slips' => $slips
]);
```
