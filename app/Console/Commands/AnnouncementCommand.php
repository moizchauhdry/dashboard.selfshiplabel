<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use App\Notifications\Shopping\StatusNotification;
use Illuminate\Console\Command;

class AnnouncementCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:announcement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $users = User::where('type', 'customer')->get();
        // foreach ($users as $key => $user) {
        //     $user = User::where('email', $user->email)->first();
        //     $notification = new AnnouncementNotification();
        //     $user->notify($notification);
        //     dump($key);
        // }


        $orders = Order::where('status', 'approved')->where('payment_status','Pending')->get();
        foreach ($orders as $key => $order) {
            $customer = User::find($order->customer_id);
            $customer->notify(new StatusNotification('approved', $order));
            dump($key);
        }

        dd('success');
    }
}
