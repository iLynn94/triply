<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaymentController extends Controller
{
    // --- BOOKING-SPECIFIC PAYMENT METHODS ---

    /**
     * Show payment page for a specific booking
     */
    public function showPayment(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if already paid
        if ($booking->payment_status === 'paid') {
            return redirect()->route('payments.receipt', $booking)->with('info', 'This booking is already paid.');
        }

        return view('payments.show', compact('booking'));
    }

    /**
     * Process payment for a specific booking
     */
    public function processBookingPayment(Request $request, Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if already paid
        if ($booking->payment_status === 'paid') {
            return redirect()->route('bookings')->with('error', 'This booking is already paid.');
        }

        $method = $request->input('payment_method');
        $amount = $booking->total_fee;

        if ($method === 'mpesa') {
            return $this->payBookingWithMpesa($request, $booking);
        } elseif ($method === 'card') {
            return $this->payBookingWithStripe($booking);
        }

        return redirect()->back()->with('error', 'Please select a payment method.');
    }

    /**
     * M-Pesa payment for booking
     */
    private function payBookingWithMpesa(Request $request, Booking $booking)
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
            'PartyA' => $phone,
            'PartyB' => $shortCode,
            'PhoneNumber' => $phone,
            'CallBackURL' => env('MPESA_CALLBACK_URL'),
            'AccountReference' => 'Booking-' . $booking->id,
            'TransactionDesc' => 'Payment for Booking #' . $booking->id
        ]);

        if ($stkResponse->successful()) {
            return redirect()->route('payments.show', $booking)->with('success', 'STK Push sent! Check your phone to complete payment.');
        } else {
            return redirect()->back()->with('error', 'M-Pesa Error: ' . $stkResponse->body());
        }
    }

    /**
     * Stripe payment for booking
     */
    private function payBookingWithStripe(Booking $booking)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $checkout_session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'kes',
                    'product_data' => [
                        'name' => 'Booking: ' . $booking->trip->title,
                        'description' => 'Booking #' . $booking->id . ' - ' . $booking->number_of_adults . ' adults, ' . $booking->number_of_children . ' children',
                    ],
                    'unit_amount' => $booking->total_fee * 100, // Stripe expects cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payments.show', $booking) . '?payment_success=true',
            'cancel_url' => route('payments.show', $booking) . '?payment_canceled=true',
            'metadata' => [
                'booking_id' => $booking->id,
            ],
        ]);

        return redirect($checkout_session->url);
    }

    /**
     * Show receipt for a paid booking
     */
    public function showReceipt(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if booking is paid
        if ($booking->payment_status !== 'paid') {
            return redirect()->route('payments.show', $booking)->with('error', 'Payment not completed yet.');
        }

        return view('payments.receipt', compact('booking'));
    }
}