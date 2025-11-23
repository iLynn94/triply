{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-sm font-medium text-gray-600 mb-2">Your Trips</h3>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalTrips) }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-sm font-medium text-gray-600 mb-2">Total Bookings</h3>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalBookings) }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-sm font-medium text-gray-600 mb-2">Open Trips</h3>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($openTrips) }}</p>
    </div>
</div>

{{-- Charts Section --}}
<div class="grid grid-cols-1 gap-6 mb-8">
    {{-- Bookings Over Time (Area Chart) --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bookings Over Time</h3>
        <p class="text-sm text-gray-600 mb-4">Monthly bookings for your trips this year</p>
        <div id="user-bookings-chart" style="height: 300px;"></div>
    </div>
</div>

{{-- Trips Table --}}
<div class="bg-white rounded-lg shadow-sm p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Your Trips</h3>
        <a href="{{ route('create-travel-package') }}" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 text-sm font-medium transition-colors">
            Create Trip
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($trips as $trip)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('trip.show', $trip) }}" class="text-orange-600 hover:text-orange-800 font-medium">
                            {{ $trip->title }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form method="POST" action="{{ route('dashboard.trips.status', $trip) }}" class="inline">
                            @csrf
                            <select
                                name="status"
                                onchange="this.form.submit()"
                                class="px-3 py-2 text-sm font-semibold rounded-lg border shadow-sm focus:border-orange-500 focus:ring-orange-500 cursor-pointer transition-colors min-w-[120px] {{ $trip->status === 'open' ? 'bg-green-50 text-green-700 border-green-200' : ($trip->status === 'full' ? 'bg-blue-50 text-blue-700 border-blue-200' : ($trip->status === 'closed' ? 'bg-gray-50 text-gray-700 border-gray-200' : 'bg-red-50 text-red-700 border-red-200')) }}"
                                {{ $trip->status === 'closed' ? 'disabled' : '' }}
                            >
                                <option value="open" {{ $trip->status === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="full" {{ $trip->status === 'full' ? 'selected' : '' }}>Full</option>
                                <option value="canceled" {{ $trip->status === 'canceled' ? 'selected' : '' }}>Canceled</option>
                                @if($trip->status === 'closed')
                                <option value="closed" selected>Closed (by admin)</option>
                                @endif
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $trip->bookings->count() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $trip->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('trips.edit', $trip) }}" class="text-blue-600 hover:text-blue-900 font-medium mr-3">Edit</a>
                        <a href="{{ route('trip.show', $trip) }}" class="text-gray-600 hover:text-gray-900 font-medium">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        <div class="py-8">
                            <p class="mb-4">You haven't created any trips yet</p>
                            <a href="{{ route('create-travel-package') }}" class="inline-block px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium transition-colors">
                                Create Your First Trip
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Bookings Table --}}
@if($trips->count() > 0)
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bookings for Your Trips</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trip</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adults/Children</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booked On</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $booking)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $booking->trip->title }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $booking->user->first_name }} {{ $booking->user->last_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $booking->number_of_adults }} / {{ $booking->number_of_children }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        KSh {{ number_format($booking->total_fee, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : ($booking->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $booking->created_at->format('M d, Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No bookings yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ApexCharts Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Bookings Area Chart
    const userBookingsChart = new ApexCharts(document.querySelector("#user-bookings-chart"), {
        series: [{
            name: 'Bookings',
            data: @json(array_column($monthlyBookings, 'bookings'))
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: { show: false }
        },
        colors: ['#ea580c'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3
            }
        },
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            categories: @json(array_column($monthlyBookings, 'month'))
        },
        yaxis: {
            title: { text: 'Number of Bookings' }
        }
    });
    userBookingsChart.render();
});
</script>
