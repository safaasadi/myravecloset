<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\AlertHelper;
use \App\StripeHelper;
use Illuminate\Support\Facades\Log;

class StripeAccount extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'customer_id', 'express_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function connect()
    {
        $code = \request('code');

        // if there is an error connecting to Stripe, abort and let user know
        if (isset($_GET['error'])) {
            if (env('APP_DEBUG')) Log::error($_GET['error']);
            AlertHelper::alertError('Something went wrong.');
            return redirect('/');
        }

        if ($code == null) return;

        $user = auth()->user();

        $stripe_account = StripeHelper::getAccountFromStripeConnect($code);
        if ($stripe_account->country == 'US' && $user->stripe_express_id == null) {
            $stripe_acc = StripeAccount::where('user_id', $user->id)->first();
            $stripe_acc->express_id = $stripe_account->id;
            $stripe_acc->save();

            $this->transferCredits($stripe_account->id);

            AlertHelper::alertSuccess('Stripe account setup! You can now accept payments.');
            return redirect('/');
        } else {
            AlertHelper::alertError('This is not a US account or you have already connected an account.');
            return redirect('/');
        }
    }

    private function transferCredits($express_id) {
        $stripe_account = auth()->user()->StripeAccount();
        $application_fee_percent = $stripe_account->application_fee_percent == null ? env('DEFAULT_COMMISSION') : $stripe_account->application_fee_percent;
        $credit_balance = \App\Models\Order::where('seller_id', auth()->user()->id)->where('credit', true)->sum('cost_item');
        $pending_credit_balance = $credit_balance - ($credit_balance * $application_fee_percent);
        
        $stripe = new \Stripe\StripeClient(env('STRIPE_CLIENT_SECRET'));

        $stripe->transfers->create([
            'amount' => 200,
            'currency' => 'usd',
            'destination' => $express_id
        ]);
    }
}
