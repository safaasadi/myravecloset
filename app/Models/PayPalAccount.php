<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\PayPalHelper;
use PayPal\Api\OpenIdSession;
use PayPal\Api\OpenIdTokeninfo;
use PayPal\Api\OpenIdUserinfo;

class PayPalAccount extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'email', 'payer_id', 'refresh_token'];

    public function getAccessParams() {
        $tokenInfo = new OpenIdTokeninfo();
        $tokenInfo = $tokenInfo->createFromRefreshToken(array('refresh_token' => $this->refresh_token), PayPayHelper::getApiContext());
        return array('access_token' => $tokenInfo->getAccessToken());
    }

}
