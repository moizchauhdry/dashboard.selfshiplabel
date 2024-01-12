<?php

namespace App\Console\Commands;

use App\Models\Package;
use App\Models\User;
use App\Notifications\PackageDestroyNotification;
use App\Notifications\PackageStorageNotification;
use Illuminate\Console\Command;

class PackageStorageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:package-storage';

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
        $packages = Package::with('customer')->orderBy('created_at', 'asc')->get();

        foreach ($packages as $key => $package) {
            calulate_storage($package);
        }

        foreach ($packages->where('storage_days', '>', 75)->where('storage_days', '<', 81) as $key => $package) {
            $user = User::where('email', $package->customer->email)->first();
            $notification = new PackageStorageNotification($package);
            $user->notify($notification);
        }

        foreach ($packages->where('storage_days', '>', 80)->where('auctioned', 0) as $key => $package) {
            $user = User::where('email', $package->customer->email)->first();
            $notification = new PackageDestroyNotification($package);
            $user->notify($notification);
            $package->update(['auctioned' => 1]);
        }

        dd('Success.');
    }
}
