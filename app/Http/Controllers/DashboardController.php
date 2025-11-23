<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get view type from request (for admins switching views)
        $view = $request->get('view', $user->isAdmin() ? 'admin' : 'user');

        // Ensure non-admins can't access admin view
        if (!$user->isAdmin() && $view === 'admin') {
            $view = 'user';
        }

        if ($view === 'admin') {
            return $this->adminDashboard($user);
        } else {
            return $this->userDashboard($user);
        }
    }

    private function adminDashboard($user)
    {
        // Stats cards
        $totalUsers = User::count();
        $totalTrips = Trip::count();
        $totalBookings = Booking::count();
        $openTrips = Trip::where('status', 'open')->count();

        // Trip status breakdown for polar chart
        $statusBreakdown = Trip::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Monthly user registrations (last 12 months) for area chart
        $monthlyUsers = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = User::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyUsers[] = [
                'month' => $month->format('M'),
                'users' => $count
            ];
        }

        // Monthly trips created (last 12 months) for bar chart
        $monthlyTrips = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Trip::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyTrips[] = [
                'month' => $month->format('M'),
                'trips' => $count
            ];
        }

        // Monthly bookings (last 12 months) for area chart
        $monthlyBookings = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Booking::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyBookings[] = [
                'month' => $month->format('M'),
                'bookings' => $count
            ];
        }

        // All trips with organizer info for table
        $trips = Trip::with('organizer', 'bookings')
            ->orderBy('created_at', 'desc')
            ->get();

        // All users for table
        $users = User::orderBy('created_at', 'desc')->get();

        return view('dashboard.index', compact(
            'user',
            'totalUsers',
            'totalTrips',
            'totalBookings',
            'openTrips',
            'statusBreakdown',
            'monthlyUsers',
            'monthlyTrips',
            'monthlyBookings',
            'trips',
            'users'
        ))->with('view', 'admin');
    }

    private function userDashboard($user)
    {
        // User's trips
        $trips = Trip::where('organizer_id', $user->id)
            ->with('bookings')
            ->orderBy('created_at', 'desc')
            ->get();

        // Stats for user's trips
        $totalTrips = $trips->count();
        $openTrips = $trips->where('status', 'open')->count();
        $totalBookings = $trips->sum(fn($trip) => $trip->bookings->count());

        // Trip status breakdown
        $statusBreakdown = $trips->groupBy('status')
            ->map(fn($group) => $group->count())
            ->toArray();

        // Monthly bookings for user's trips (last 12 months)
        $monthlyBookings = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Booking::whereIn('trip_id', $trips->pluck('id'))
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyBookings[] = [
                'month' => $month->format('M'),
                'bookings' => $count
            ];
        }

        // All bookings for user's trips
        $bookings = Booking::whereIn('trip_id', $trips->pluck('id'))
            ->with(['trip', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.index', compact(
            'user',
            'trips',
            'totalTrips',
            'openTrips',
            'totalBookings',
            'statusBreakdown',
            'monthlyBookings',
            'bookings'
        ))->with('view', 'user');
    }

    public function updateTripStatus(Request $request, Trip $trip)
    {
        $user = auth()->user();

        // Only admin can set status to 'closed'
        if ($request->status === 'closed' && !$user->isAdmin()) {
            return back()->with('error', 'Only admins can close trips');
        }

        // Users can only update their own trips
        if (!$user->isAdmin() && $trip->organizer_id !== $user->id) {
            return back()->with('error', 'Unauthorized');
        }

        $trip->update(['status' => $request->status]);

        return back()->with('success', 'Trip status updated successfully');
    }

    public function revokeVerification(Request $request, User $user)
    {
        $targetUser = $user; // Route model binding uses {user} parameter
        $currentUser = auth()->user();

        if (!$currentUser->isAdmin()) {
            return back()->with('error', 'Unauthorized');
        }

        // Prevent revoking own verification
        if ($targetUser->id === $currentUser->id) {
            return back()->with('error', 'You cannot revoke your own verification');
        }

        $targetUser->is_verified = false;
        $targetUser->save();

        return back()->with('success', 'User verification revoked successfully');
    }

    public function changeUserRole(Request $request, User $user)
    {
        $targetUser = $user; // Route model binding uses {user} parameter
        $currentUser = auth()->user();

        if (!$currentUser->isAdmin()) {
            return back()->with('error', 'Unauthorized');
        }

        // Prevent changing own role
        if ($targetUser->id === $currentUser->id) {
            return back()->with('error', 'You cannot change your own role');
        }

        // Validate and update role - same pattern as trip status
        if (!in_array($request->role, ['user', 'admin'])) {
            return back()->with('error', 'Invalid role');
        }

        $targetUser->role = $request->role;
        $targetUser->save();

        return back()->with('success', 'User role updated to ' . $request->role . ' successfully');
    }
}
