<?php

namespace App\Console\Commands;

use App\Enums\Role;
use App\Models\Appointment;
use App\Models\Slip;
use App\Models\User;
use Illuminate\Console\Command;

class InitializeTestBench extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialize-test-bench';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle()
    {
        $this->info("Creating Super Admin");
        //create a superadmin
        $superAdmin = new User();
        $superAdmin
            ->forceFill([
                'role' => Role::SuperAdmin,
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => bcrypt('superadmin'),
            ])
            ->saveOrFail();

        $this->info("Creating Five Maintainer-Agent");
        //create five maintainer-agents
        foreach (range(1, 3) as $ma) {
            $username = "maintainer_agent_$ma";
            $maintainerAgent = new User();
            $maintainerAgent
                ->forceFill([
                    'role' => Role::Agent,
                    'is_maintainer' => true,
                    'name' => 'Maintainer Agent ' . $ma,
                    'email' => $username . '@example.com',
                    'password' => bcrypt($username),
                ])
                ->saveOrFail();

            //maintainer agent cannot create staffs
            //so, we don't create staffs


            //now maintainer-agent and normal agent both can have customers
            //so we create customers for maintainer-agent here:
            foreach (range(1, 3) as $maintainerCustomerIndex) {
                $this->info("Creating Customers for Maintainer Agent: {$maintainerAgent->name}");
                $customer = $this->createCustomerForMaintainerAgent($maintainerAgent, $maintainerCustomerIndex);

                //this customer is maintainer, so it can create staffs
                foreach (range(1, 3) as $ci) {
                    $this->info("Creating Staff for Customer: {$customer->name}");
                    $this->createStaff($customer, $ci);
                }
            }

            //create five agents belongs to this maintainer-agent
            foreach (range(1, 3) as $a) {
                $this->info("Creating Agent for Maintainer Agent: {$maintainerAgent->name}");
                $agent = $this->createAgent($maintainerAgent, $a);

                //this agent can create customers with maintaining capabilities
                //so let's create five customers
                foreach (range(1, 3) as $ac) {
                    $this->info("Creating Customer for Agent: {$agent->name}");
                    $customer = $this->createCustomerForAgent($agent, $ac);

                    //this customer can create other customers (staff),
                    //without maintaining capability

                    foreach (range(1, 3) as $ci) {
                        $this->info("Creating Staff for Customer: {$customer->name}");
                        $this->createStaff($customer, $ci);
                    }
                }

            }
        }

        //create appointments
        $this->info("Creating Appointments");

        $this->output->progressStart(
            User::query()
                ->where('role', '!=', Role::SuperAdmin)
                ->count()
        );
        User::query()
            ->where('role', '!=', Role::SuperAdmin)
            ->each(function (User $user) {
                $this->createAppointment($user);
                $this->output->progressAdvance();
            });
        $this->output->progressFinish();


        //create slips
        $this->info("Creating Slips");
        $this->output->progressStart(Appointment::query()->count());
        Appointment::query()->each(function (Appointment $appointment) {
            $this->createSlip($appointment);
            $this->output->progressAdvance();
        });
        $this->output->progressFinish();
    }

    /**
     * Maintainer Agent can create customers as maintainer customer
     * @throws \Throwable
     */
    private function createCustomerForMaintainerAgent(User $maintainerAgent, int $index): User
    {
        $username = "maintainer_agents_customer_{$maintainerAgent->id}_.$index";

        $customer = new User();
        $customer
            ->forceFill([
                'role' => Role::Customer,
                'is_maintainer' => true,
                'name' => "Maintainer Agent's Customer - M: {$maintainerAgent->id} Index - {$index}",
                'email' => "$username@example.com",
                'password' => bcrypt($username),
                'agent_id' => $maintainerAgent->id,
            ])
            ->saveOrFail();

        $this->info("Customer : {$customer->name} created");
        return $customer;
    }

    /**
     * Maintainer Agent can create customers as maintainer customer
     * @throws \Throwable
     */
    private function createCustomerForAgent(User $agent, int $index): User
    {
        $username = "agent_customer_{$agent->id}_.$index";

        $customer = new User();
        $customer
            ->forceFill([
                'role' => Role::Customer,
                'is_maintainer' => true,
                'name' => "Agent's Customer - M: {$agent->id} Index - {$index}",
                'email' => "$username@example.com",
                'password' => bcrypt($username),
                'agent_id' => $agent->id,
            ])
            ->saveOrFail();

        $this->info("Customer : {$customer->name} created");
        return $customer;
    }

    /**
     * Maintainer Agent can create Agents without maintaining capability
     * @throws \Throwable
     */
    private function createAgent(User $maintainerAgent, int $index): User
    {
        $username = "agent_{$maintainerAgent->id}_.$index";

        $agent = new User();
        $agent
            ->forceFill([
                'role' => Role::Agent,
                'is_maintainer' => false,
                'name' => "Agent of Maintainer : {$maintainerAgent->id} Index - {$index}",
                'email' => "$username@example.com",
                'password' => bcrypt($username),
                'agent_id' => $maintainerAgent->id,
            ])
            ->saveOrFail();

        $this->info("Agent : {$agent->name} created");
        return $agent;
    }

    /**
     * whether it is created by the customer that belongs to Agent or Maintainer Agent
     * it is always same
     * @throws \Throwable
     */
    private function createStaff(User $customer, int $index): User
    {
        $username = "staff_{$customer->id}_.$index";

        $staff = new User();
        $staff
            ->forceFill([
                'role' => Role::Customer,
                'is_maintainer' => false,
                'name' => "Staff - M: {$customer->id} Index - {$index}",
                'email' => "$username@example.com",
                'password' => bcrypt($username),
                'agent_id' => $customer->agent_id,
                'customer_id' => $customer->id,
            ])
            ->saveOrFail();

        $this->info("Staff : {$staff->name} created");
        return $staff;
    }

    private function createAppointment(User $user)
    {
        return Appointment::factory(500)->create([
            "user_id" => $user->id,
            "customer_id" => $user->customer_id,
            "agent_id" => $user->agent_id,
            "user_role" => $user->role,
        ]);
    }

    private function createSlip(Appointment $appointment)
    {
        $user = $appointment->user;

        return Slip::factory()->create([
            "appointment_id" => $appointment->id,
            "user_id" => $user->id,
            "customer_id" => $user->customer_id,
            "agent_id" => $user->agent_id,
            "user_role" => $user->role,
        ]);
    }
}


