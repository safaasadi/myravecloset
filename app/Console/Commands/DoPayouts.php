<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Models\Order;
use \App\Models\User;
use \App\Models\PayPalAccount;
use \App\PayPalHelper;
use Carbon\Carbon;

class DoPayouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:payouts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check orders that "paid_out" = false to resend the money to.';

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
        PayPalHelper::payout(Order::where('paid_out', false)->where('captured', true)->where('refund_cutoff', '<=', Carbon::now())->whereNotNull('label_url')->get());
        return 0;
    }
}
