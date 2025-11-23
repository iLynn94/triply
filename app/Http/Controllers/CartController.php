<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Get all unpaid items for the logged-in user
        $cartItems = Cart::where('user_id', Auth::id())->with('trip')->get();
        $total = $cartItems->sum('subtotal');

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function store(Request $request, $tripId)
    {
        if (!Auth::check()) {
            return redirect()->route('sign-in');
        }

        $trip = Trip::findOrFail($tripId);
        $people = $request->input('people', 1);

        // Check if item exists to avoid duplicates
        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('trip_id', $tripId)
                        ->first();

        if ($cartItem) {
            $cartItem->people += $people;
            $cartItem->subtotal = $cartItem->people * $trip->price;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'trip_id' => $trip->id,
                'people' => $people,
                'subtotal' => $trip->price * $people,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Trip added to your plan!');
    }

    public function destroy($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Item removed.');
    }
}