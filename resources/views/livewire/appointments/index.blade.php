<div class="container m-auto mt-3 pb-3">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Title
                </th>
                <th scope="col" class="px-6 py-3">
                    Date
                </th>
                <th scope="col" class="px-6 py-3">
                    User Role
                </th>
                <th scope="col" class="px-6 py-3">
                    Agent
                </th>
                <th scope="col" class="px-6 py-3">
                    Customer
                </th>
                <th scope="col" class="px-6 py-3">
                    User (Creator)
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
            /** @var \App\Models\Appointment $appointment * */
            ?>
            @foreach($appointments as $appointment)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{$appointment->id}}
                    </th>
                    <td class="px-6 py-4">
                        {{$appointment->title}}
                    </td>
                    <td class="px-6 py-4">
                        {{$appointment->appointment_date}}
                    </td>
                    <td class="px-6 py-4">
                        {{$appointment->user_role}}
                    </td>
                    <td class="px-6 py-4">
                        {{$appointment->agent?->name}}
                    </td>
                    <td class="px-6 py-4">
                        {{$appointment->customer?->name}}
                    </td>
                    <td class="px-6 py-4">
                        {{$appointment->user?->name}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{$appointments->links()}}
    </div>
</div>
