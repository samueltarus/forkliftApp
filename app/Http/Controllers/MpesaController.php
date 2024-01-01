<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class MpesaController extends Controller
{
    public function initiatePayment(Request $request)
    {
        return "done";
        $consumerKey = 'CpAvsTo4G23ALGJp4MgaeJDFIWMpTYsE';
        $consumerSecret = '2dIhebCpa3TjlARS';
        $lipaNaMpesaOnlinePasskey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
        $lipaNaMpesaOnlineShortcode = '174379';
        $lipaNaMpesaOnlineCallbackUrl = 'call-bakcul';

        $phone = $request->input('phone');
        $amount = $request->input('amount');

        $client = new Client();

        $response = $client->post('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($consumerKey . ':' . $consumerSecret),
            ],
        ]);

        $accessToken = json_decode($response->getBody())->access_token;

        $stkPushData = [
            'BusinessShortCode' => $lipaNaMpesaOnlineShortcode,
            'Password' => base64_encode($lipaNaMpesaOnlineShortcode . $lipaNaMpesaOnlinePasskey . date('YmdHis')),
            'Timestamp' => date('YmdHis'),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $lipaNaMpesaOnlineShortcode,
            'PhoneNumber' => $phone,
            'CallBackURL' => $lipaNaMpesaOnlineCallbackUrl,
            'AccountReference' => 'YourReference',
            'TransactionDesc' => 'Payment for services',
        ];

        $response = $client->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $stkPushData,
        ]);

        $responseData = json_decode($response->getBody());

        $transaction = Transaction::create([
            'transaction_id' => $responseData->CheckoutRequestID,
            'phone' => $phone,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        return view('payment.confirmation', compact('transaction'));
    }

    public function handleCallback(Request $request)
    {
        $transactionId = $request->input('TransID');
        $status = $request->input('TransStatus');
        $transaction = Transaction::where('transaction_id', $transactionId)->first();
        if ($transaction) {
            if ($status == 'Success') {
                $transaction->status = 'Success';
            } else {
                $transaction->status = 'Failed';
            }

            $transaction->save();
        }
        return view('payment.confirmation', compact('transaction'));
    }
}

