<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())
                          ->where('status', 'pending')
                          ->with('trip')
                          ->get();
        $total = $payments->sum('amount');
        return view('payment.index', compact('payments', 'total'));
    }

    public function store(Request $request, $id)
    {
        if (!Auth::check()) return redirect()->route('sign-in');
        $trip = Trip::findOrFail($id);
        $people = $request->input('people', 1);
        $amount = $trip->price * $people;

        Payment::create([
            'user_id' => Auth::id(),
            'trip_id' => $trip->id,
            'amount'  => $amount,
            'status'  => 'pending',
            'payment_method' => 'mpesa',
        ]);

        return redirect()->route('payment.index')->with('success', 'Booking initiated.');
    }

    public function destroy($id)
    {
        Payment::where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Payment cancelled.');
    }

    // --- NEW: PROCESS PAYMENT LOGIC ---
    public function process(Request $request)
    {
        $method = $request->input('payment_method');
        $amount = $request->input('total_amount'); // Passed from hidden input

        if ($amount <= 0) {
            return redirect()->back()->with('error', 'No pending payments found.');
        }

        if ($method === 'mpesa') {
            return $this->payWithMpesa($request);
        } elseif ($method === 'card') {
            return $this->payWithStripe($amount);
        }

        return redirect()->back()->with('error', 'Please select a payment method.');
    }

    // --- M-PESA STK PUSH ---
    private function payWithMpesa(Request $request)
    {
        $phone = $request->input('phone_number');
        
        // 1. Get Access Token
        $consumerKey = env('MPESA_CONSUMER_KEY');
        $consumerSecret = env('MPESA_CONSUMER_SECRET');
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $response = Http::withBasicAuth($consumerKey, $consumerSecret)->get($url);
        $accessToken = $response['access_token'] ?? null;

        if (!$accessToken) {
            return redirect()->back()->with('error', 'Failed to connect to M-Pesa. Check keys.');
        }

        // 2. STK Push Request
        $stkUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $shortCode = env('MPESA_SHORTCODE');
        $passkey = env('MPESA_PASSKEY');
        $timestamp = date('YmdHis');
        $password = base64_encode($shortCode . $passkey . $timestamp);

        // Simple amount for testing (M-Pesa sandbox fails with large amounts sometimes)
        $amount = 1; 

        $stkResponse = Http::withToken($accessToken)->post($stkUrl, [
            'BusinessShortCode' => $shortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone, // User's phone number
            'PartyB' => $shortCode,
            'PhoneNumber' => $phone,
            'CallBackURL' => env('MPESA_CALLBACK_URL'),
            'AccountReference' => 'TriplyTravel',
            'TransactionDesc' => 'Payment for Trip'
        ]);

        if ($stkResponse->successful()) {
            return redirect()->back()->with('success', 'STK Push sent! Check your phone.');
        } else {
            return redirect()->back()->with('error', 'M-Pesa Error: ' . $stkResponse->body());
        }
    }

    // --- STRIPE CHECKOUT ---
    private function payWithStripe($amount)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $checkout_session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'kes',
                    'product_data' => [
                        'name' => 'Triply Booking Total',
                    ],
                    'unit_amount' => $amount * 100, // Stripe expects cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.index') . '?success=true',
            'cancel_url' => route('payment.index') . '?canceled=true',
        ]);

        return redirect($checkout_session->url);
    }
}