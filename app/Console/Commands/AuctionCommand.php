<?php

namespace App\Console\Commands;

use App\Models\Auction;
use App\Models\User;
use App\Notifications\BidderSelectionNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AuctionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:auction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command checks expiry';

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
        $auctions = Auction::whereNull('expired_at')->get();
        $now = Carbon::now();
        foreach ($auctions as $key => $auction) {
            if ($auction->ending_at <= $now) {
                $auction->expired_at = $now;
                $auction->save();
                $checkBid = $auction->bids()->where('is_selected', 1)->count();
                if ($checkBid == 0) {
                    $highestBid = $auction->bids()->orderBy('amount', 'desc')->first();
                    if ($highestBid != null) {
                        $highestBid->is_selected = 1;
                        $highestBid->save();
                        $customer = User::find($highestBid->bidder_id);
                        $customer->notify(new BidderSelectionNotification($highestBid));
                        $auction->update([
                            'winner_id' => $customer->id,
                            'final_price' => $highestBid->amount,
                        ]);
                    }
                }
            }
            \dump($key);
        }
    }
}
