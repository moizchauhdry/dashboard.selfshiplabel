<?php

namespace App\Console\Commands;

use App\Models\Shipping;
use App\Models\ShippingService;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UserShippingServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:user-shipping-service';

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

    public function getDefaultPercentage($id)
    {
        $service = ShippingService::where('id', $id)->first();
        return $service->markup_percentage;
    }

    public function handle()
    {
        $existingServiceIds = ShippingService::orderBy('id')->pluck('id')->toArray();
        $assignedServiceIds = DB::table('user_shipping_services')
            ->distinct()
            ->pluck('shipping_service_id')
            ->toArray();

        $newServiceIds = array_diff($existingServiceIds, $assignedServiceIds);

        if (empty($newServiceIds)) {
            $this->info('No new services to assign.');
            return;
        }

        $users = User::where('account_type', 2)->get();
        foreach ($users as $user) {
            $assignments = [];
            foreach ($newServiceIds as $serviceId) {
                $assignments[$serviceId] = ['markup_percentage' => $this->getDefaultPercentage($serviceId)];
            }
            $user->shippingServices()->syncWithoutDetaching($assignments);
        }

        $this->info('New services assigned successfully.');
    }
}
