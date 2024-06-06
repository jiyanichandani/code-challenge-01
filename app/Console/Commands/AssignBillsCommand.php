<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bill;
use App\Models\User;

class AssignBillsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-bills-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $users = User::withCount('bills')->get();

            $bills = Bill::whereHas('stage', function($query) {
                $query->where('label', 'Submitted');
            })->doesntHave('users')->get();

            if ($bills->isEmpty()) {
                $this->info('No bills available for assignment.');
                return;
            }

            foreach ($bills as $bill) {
                $user = $users->sortBy('bills_count')->first();
                if ($user->bills_count < 3) {
                    $user->bills()->attach($bill->id, ['created_at' => now(), 'updated_at' => now()]);
                    $user->bills_count++;
                    $this->info("Bill {$bill->id} assigned to User {$user->id}.");
                } else {
                    $this->warn("User {$user->name} has reached the maximum number of assigned bills (3). Skipping assignment for Bill {$bill->id}.");
                }
            }
            $this->info('Bills assigned successfully.');
        } catch (Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}
