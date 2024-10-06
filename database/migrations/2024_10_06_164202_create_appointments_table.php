<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * Find all appointments related to the user:
         * select * from appointments where agent_id = auth()->id() or customer_id = auth()->id() or user_id = auth()->id()
         */

        /**
         * match(user_role):
         *      staff =>
         *              user_role = required
         *              user_id = required
         *              customer_id = required,
         *              agent_id    = required
         *
         *      customer =>
         *              user_role = required
         *              user_id = required
         *              customer_id = null
         *              agent_id = required
         *
         *      agent =>
         *              user_role = required
         *              user_id = required
         *              customer_id = null
         *              agent_id = required
         *
         *      agent(maintainer)=>
         *              user_role = required
         *              user_id = required
         *              customer_id = null
         *              agent_id = null
         */

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->string('user_role');
            //the agent by whom the customer is created.
            $table->foreignId('agent_id')
                ->nullable();


            //the customer by whom the appointment is created
            $table->foreignId('customer_id')->nullable();

            //the appointment creator
            $table->foreignId('user_id');

            $table->string('title');
            $table->date('appointment_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
