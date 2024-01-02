<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{




    function initiatePayment(Request $request)
    {
        $consumerKey = 'CpAvsTo4G23ALGJp4MgaeJDFIWMpTYsE';
        $consumerSecret = '2dIhebCpa3TjlARS';
        $lipaNaMpesaOnlinePasskey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
        $lipaNaMpesaOnlineShortcode = '174379';
        $lipaNaMpesaOnlineCallbackUrl = 'https://2b1d-105-163-156-220.ngrok-free.app/handleCallback';
//        $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest'; // for production
        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'; // for test
        $phone = $request->input('phone');
        $amount = $request->input('amount');
        $phoneNumber = (substr($phone, 0, 1) == "+") ? str_replace("+", "", $phone) : $phone;
        $phoneNumber = (substr($phone, 0, 1) == "0") ? preg_replace("/^0/", "254", $phoneNumber) : $phoneNumber;
        $phoneNumber = (substr($phone, 0, 1) == "7") ? "254{$phoneNumber}" : $phoneNumber;
        $curl_post_data = [
            'BusinessShortCode' => $lipaNaMpesaOnlineShortcode,
            'Password' => $this->generatePassword($lipaNaMpesaOnlinePasskey, $lipaNaMpesaOnlineShortcode),
            'Timestamp' => $this->generateTimestamp(),
            'Amount' => $amount,
            'PartyA' => $phoneNumber,
            'TransactionType' => 'CustomerPayBillOnline',
            'PartyB' => $lipaNaMpesaOnlineShortcode,
            'PhoneNumber' => $phoneNumber,
            'CallBackURL' => $lipaNaMpesaOnlineCallbackUrl,
            'AccountReference' => 'Forklift',
            'TransactionDesc' => 'Payment for services',
        ];
        $data_string = json_encode($curl_post_data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $this->generateAccessToken($consumerKey, $consumerSecret)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $response = curl_exec($curl);
        // Save the payment details to the database
        Log::info('$response', [$response]);
        $response = json_decode($response, true);

        if ($response['ResponseCode'] == '0') {
            $checkoutRequestId = $response['CheckoutRequestID'];
            $merchantRequestId = $response['MerchantRequestID'];
//            $callbackMetadata = $response['Body']['stkCallback']['CallbackMetadata']['Item'];
//            $mpesaReceiptNumber = '';
//            foreach ($callbackMetadata as $item) {
//                if ($item['Name'] == 'MpesaReceiptNumber') {
//                    $mpesaReceiptNumber = $item['Value'];
//                    break;
//                }
//            }
            $transaction = Transaction::create([
                'transaction_id' => $checkoutRequestId,
                'merchant_request_id'=>$merchantRequestId,
                'mpesa_receipt_number'=>'',
                'phone' => $phone,
                'amount' => $amount,
                'status' => 'pending',
            ]);
            Log::info('$response', [$transaction]);
            $returnData = "<div class='alert alert-success'><strong>Success! Payment Request has been sent to " . $_POST['phone'] . ".Check your phone and enter Pin </strong></div>";

        } else {
            $returnData = "<div class='alert alert-danger'><strong> Error! </strong> " . $response['Error'] . "</div>";
        }

        return view('payment.confirmation', compact('transaction'));
//        return $response;

    }
    public function handleCallback(Request $request)
    {
        Log::info('handleCallback', [$request]);
        Log::info('handleCallback', [$request]);

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

    public function checkout()
    {
        return view('payment.initiate');
    }

    function generateAccessToken($consumerKey, $consumerSecret)
    {

//        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';  // for production
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'; //for simulation
        $curl = curl_init();
        $credentials = base64_encode($consumerKey . ":" . $consumerSecret);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        $accessToken = json_decode($response)->access_token;
        return $accessToken;

    }

    function savePaymentRequest($transactionId, $amount, $phoneNumber, $transactionType, $accountReference, $checkoutRequestId, $merchantRequestId)
    {
        try {

        } catch (PDOException $e) {
            error_log('Database Error: ' . $e->getMessage());
        }
    }

    function generatePassword($passKey, $businessShortCode)
    {
        $timestamp = $this->generateTimestamp();
        //generate password
        $mpesaPassword = base64_encode($businessShortCode . $passKey . $timestamp);
        return $mpesaPassword;
    }

    function generateTimestamp()
    {
        return date('YmdHis');
    }
}

