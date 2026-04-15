<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahProduk = Product::count();
        $jumlahCategori = ProductCategory::count();
        $jumlahOrder = Order::count();
        $jumlahStock = Product::sum('stock');
        $jumlahklikProduk = 200;

        $data = [
            ['label' => 'Jumlah Produk', 'value' => $jumlahProduk, 'color' => '#4CAF50', 'icon' => 'inventory_2'],
            ['label' => 'Jumlah Kategori', 'value' => $jumlahCategori, 'color' => '#2196F3', 'icon' => 'category'],
            ['label' => 'Jumlah Order', 'value' => $jumlahOrder, 'color' => '#FF9800', 'icon' => 'shopping_cart'],
            ['label' => 'Jumlah Klik Produk', 'value' => $jumlahklikProduk, 'color' => '#f44336', 'icon' => 'touch_app'],
            ['label' => 'Jumlah Stock', 'value' => $jumlahStock, 'color' => '#9C27B0', 'icon' => 'box'],
        ];

        $chartData = self::orderData();
        $latestOrders = Order::latest()->take(5)->get();

        return view('dashboard', compact('data', 'chartData', 'latestOrders'));
    }


    // Array Data dummy untuk grafik penjualan 7 hari terakhir (tanggal D-M-Y, jumlah order, total pendapatan)
    public static function orderData()
    {
        $today = Carbon::now();
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $labels[] = $today->copy()->subDays($i)->format('d-m-Y');
        }

        return [
            'labels' => [
                carbon::now()->subDays(6)->format('d-m-Y'),
                carbon::now()->subDays(5)->format('d-m-Y'),
                carbon::now()->subDays(4)->format('d-m-Y'),
                carbon::now()->subDays(3)->format('d-m-Y'),
                carbon::now()->subDays(2)->format('d-m-Y'),
                carbon::now()->subDays(1)->format('d-m-Y'),
                carbon::now()->format('d-m-Y'),
            ],
            'orderCounts' => [28, 34, 45, 40, 52, 47, 63],
            'revenueAmounts' => [95000, 120000, 140000, 130000, 165000, 158000, 190000],
        ];
    }
}