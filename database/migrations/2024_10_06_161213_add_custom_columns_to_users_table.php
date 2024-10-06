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
         * Story:
         * is_maintainer:
         *      when available it denotes the user to be either maintainer customer or maintainer agent.
         * role:
         *      it denotes the role of the user
         *
         * Cases:
         *      1. when, role = agent & is_maintainer = true
         *          => This user is maintainer agent, created by super admin
         *      capabilities:
         *          -> It can create other agents as non-maintainer agent
         *
         *      dependencies:
         *          -> agent_id = null
         *          -> role = agent
         *          -> is_maintainer = true
         *
         *      2. when, role = agent, is_maintainer = false
         *              => only agent, created by maintainer agent, and belongs to that agent.
         *
         *          capabilities:
         *              -> manage customers
         *
         *          dependencies:
         *              -> agent_id = required (id of maintainer agent)
         *              -> role = agent
         *              -> is_maintainer = false
         *
         *
         *      3. when,  role = customer, is_maintainer=true it is maintainer customer
         *
         *          capabilities:
         *              -> it can create staffs (refers to customers without maintaining capabilities)
         *
         *          dependencies:
         *              ->role  = required (customer)
         *              -> agent_id = required
         *              -> customer_id = required (team_leader_id)
         *              -> is_maintainer = true
         *
         *      4. when, role = customer, is_maintainer=false it is only customer
         *
         *          dependencies:
         *              -> role = customer
         *              -> agent_id = required
         *              -> customer_id = required
         *              -> is_maintainer = false
         *
         *
         */
        Schema::table('users', function (Blueprint $table) {

            $table->string('role');
            $table->boolean('is_maintainer')->default(false);


            /**
             * This is the user_id to whom it belongs to.
             * If it is itself an agent and not a maintainer agent, it will be filled,
             * otherwise null.
             */
            $table
                ->foreignId('agent_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete() //when parent is deleted this item will be deleted
                ->cascadeOnUpdate();    //when parent is updated this item will be updated

            $table
                ->foreignId('customer_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();


            //staff doesn't need anything
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
