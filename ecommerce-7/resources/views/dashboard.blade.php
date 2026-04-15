<x-app-layout :title="'Dashboard'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-2 grid gap-4">
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 lg:gap-4 gap-2">
                @foreach($data as $index => $item)
                    <div class="overflow-hidden shadow-sm sm:rounded-lg p-6" style="background-color: {{ $item['color'] }};">
                        <div class="flex justify-between items-center">
                            <p class="mt-2 text-3xl font-bold text-white">{{ $item ['value'] }}</p>
                            <span class="material-symbols-outlined text-white text-[48px]">{{ $item['icon'] }}</span>
                        </div>
                        <hr class="my-2">
                        <h3 class="text-lg font-medium text-white">{{ $item ['label'] }}</h3>
                    </div>
                @endforeach
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Grafik 7 Hari Penjualan</h3>
                <div class="w-full">
                    <canvas id="salesChart" class="w-full h-80"></canvas>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Order Terbaru</h3>
                {{-- table for latest orders --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Order Number
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Customer
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Harga
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($latestOrders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $order->order_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $order->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($order->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center">
                                Tidak ada order terbaru.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const labels = @json($chartData['labels']);
            const orderCounts = @json($chartData['orderCounts']);
            const revenueAmounts = @json($chartData['revenueAmounts']);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Jumlah Order',
                            data: orderCounts,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.25)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 4,
                        },
                        {
                            label: 'Total Pendapatan (Rp)',
                            data: revenueAmounts,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.25)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 4,
                            yAxisID: 'revenue',
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Order',
                            },
                        },
                        revenue: {
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false,
                            },
                            title: {
                                display: true,
                                text: 'Pendapatan (Rp)',
                            },
                        },
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    if (context.dataset.label === 'Total Pendapatan (Rp)') {
                                        return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString();
                                    }
                                    return context.dataset.label + ': ' + context.parsed.y;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
