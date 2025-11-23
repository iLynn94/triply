{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-sm font-medium text-gray-600 mb-2">Total Users</h3>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-sm font-medium text-gray-600 mb-2">Total Trips</h3>
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
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Monthly Trips (Bar Chart) --}}
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Trips</h3>
        <p class="text-sm text-gray-600 mb-4">Trips created per month this year</p>
        <div id="monthly-trips-chart" style="height: 300px;"></div>
    </div>

    {{-- Trip Status (Polar Chart) --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Trip Status</h3>
        <p class="text-sm text-gray-600 mb-4">Current status breakdown</p>
        <div id="status-breakdown-chart" style="height: 300px;"></div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 mb-8">
    {{-- User Registrations (Area Chart) --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">User Registrations</h3>
        <p class="text-sm text-gray-600 mb-4">Monthly user growth this year</p>
        <div id="user-registrations-chart" style="height: 300px;"></div>
    </div>

    {{-- Bookings (Area Chart) --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bookings</h3>
        <p class="text-sm text-gray-600 mb-4">Monthly bookings this year</p>
        <div id="bookings-chart" style="height: 300px;"></div>
    </div>
</div>

{{-- Trips Table --}}
<div class="bg-white rounded-lg shadow-sm p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">All Trips</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organizer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $trip->organizer->first_name }} {{ $trip->organizer->last_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form method="POST" action="{{ route('dashboard.trips.status', $trip) }}" class="inline">
                            @csrf
                            <select
                                name="status"
                                onchange="this.form.submit()"
                                class="px-3 py-2 text-sm font-semibold rounded-lg border shadow-sm focus:border-orange-500 focus:ring-orange-500 cursor-pointer transition-colors min-w-[120px] {{ $trip->status === 'open' ? 'bg-green-50 text-green-700 border-green-200' : ($trip->status === 'closed' ? 'bg-gray-50 text-gray-700 border-gray-200' : ($trip->status === 'full' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-red-50 text-red-700 border-red-200')) }}"
                            >
                                <option value="open" {{ $trip->status === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="full" {{ $trip->status === 'full' ? 'selected' : '' }}>Full</option>
                                <option value="canceled" {{ $trip->status === 'canceled' ? 'selected' : '' }}>Canceled</option>
                                <option value="closed" {{ $trip->status === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $trip->bookings->count() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $trip->created_at->format('M d, Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No trips found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Users Table --}}
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">All Users</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $userItem)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $userItem->first_name }} {{ $userItem->last_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $userItem->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $userItem->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($userItem->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($userItem->isVerified())
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Verified
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Not Verified
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $userItem->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex items-center gap-2">
                            {{-- Role Change Select --}}
                            @if($userItem->id !== $user->id)
                            <form method="POST" action="{{ route('dashboard.users.change-role', $userItem) }}" class="inline" id="role-form-{{ $userItem->id }}">
                                @csrf
                                <select
                                    name="role"
                                    onchange="document.getElementById('role-form-{{ $userItem->id }}').submit();"
                                    class="px-3 py-2 text-sm font-semibold rounded-lg border shadow-sm focus:border-orange-500 focus:ring-orange-500 cursor-pointer transition-colors min-w-[100px] {{ $userItem->role === 'admin' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-gray-50 text-gray-700 border-gray-200' }}"
                                >
                                    <option value="user" {{ $userItem->role === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $userItem->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </form>
                            @endif

                            {{-- Revoke Verification --}}
                            @if($userItem->isVerified() && $userItem->id !== $user->id)
                            <div x-data="{ showRevokeModal: false }">
                                <button
                                    @click="showRevokeModal = true"
                                    type="button"
                                    class="text-red-600 hover:text-red-900 font-medium text-xs"
                                >
                                    Revoke
                                </button>

                                {{-- Confirmation Modal --}}
                                <div
                                    x-show="showRevokeModal"
                                    x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
                                    @click.self="showRevokeModal = false"
                                    style="display: none;"
                                >
                                    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4">
                                        <div class="flex items-center gap-4 mb-4">
                                            <div class="shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-900">Revoke Verification?</h3>
                                            </div>
                                        </div>
                                        <p class="text-gray-600 mb-6 wrap-break-word overflow-wrap-anywhere">
                                            Are you sure you want to revoke verification for <strong class="break-normal">{{ $userItem->first_name }} {{ $userItem->last_name }}</strong>?
                                            This action can be undone later by verifying them again.
                                        </p>
                                        <div class="flex gap-3 justify-end">
                                            <button
                                                type="button"
                                                @click="showRevokeModal = false"
                                                class="px-4 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-100 font-semibold text-gray-700 transition-all"
                                            >
                                                Cancel
                                            </button>
                                            <form method="POST" action="{{ route('dashboard.users.revoke-verification', $userItem) }}" class="inline">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition-all"
                                                >
                                                    Revoke Verification
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ApexCharts Scripts --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Trips Bar Chart
    const monthlyTripsChart = new ApexCharts(document.querySelector("#monthly-trips-chart"), {
        series: [{
            name: 'Trips',
            data: @json(array_column($monthlyTrips, 'trips'))
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: { show: false }
        },
        colors: ['#ea580c'],
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '60%'
            }
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: @json(array_column($monthlyTrips, 'month'))
        },
        yaxis: {
            title: { text: 'Number of Trips' }
        }
    });
    monthlyTripsChart.render();

    // Trip Status Polar Chart
    const statusData = @json($statusBreakdown);
    const statusChart = new ApexCharts(document.querySelector("#status-breakdown-chart"), {
        series: [
            statusData.open || 0,
            statusData.full || 0,
            statusData.canceled || 0,
            statusData.closed || 0
        ],
        chart: {
            type: 'polarArea',
            height: 300
        },
        labels: ['Open', 'Full', 'Canceled', 'Closed'],
        colors: ['#22c55e', '#3b82f6', '#ef4444', '#6b7280'],
        stroke: {
            colors: ['#fff']
        },
        fill: {
            opacity: 0.8
        },
        legend: {
            position: 'bottom'
        }
    });
    statusChart.render();

    // User Registrations Area Chart
    const userRegistrationsChart = new ApexCharts(document.querySelector("#user-registrations-chart"), {
        series: [{
            name: 'Users',
            data: @json(array_column($monthlyUsers, 'users'))
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
            categories: @json(array_column($monthlyUsers, 'month'))
        },
        yaxis: {
            title: { text: 'Number of Users' }
        }
    });
    userRegistrationsChart.render();

    // Bookings Area Chart
    const bookingsChart = new ApexCharts(document.querySelector("#bookings-chart"), {
        series: [{
            name: 'Bookings',
            data: @json(array_column($monthlyBookings, 'bookings'))
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: { show: false }
        },
        colors: ['#3b82f6'],
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
    bookingsChart.render();
});
</script>
