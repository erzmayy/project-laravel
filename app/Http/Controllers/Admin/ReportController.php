<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        // Summary Statistics
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->sum('total_amount');
        
        $completedOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->count();
        
        $pendingOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pending')
            ->count();

        // Orders by Date
        $ordersByDate = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top Products
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // Revenue by Payment Method
        $revenueByPayment = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('payment_method')
            ->get();

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalOrders',
            'totalRevenue',
            'completedOrders',
            'pendingOrders',
            'ordersByDate',
            'topProducts',
            'revenueByPayment'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $orders = Order::with(['user', 'items.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $filename = "laporan-transaksi-{$startDate}-to-{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['No. Order', 'Tanggal', 'Customer', 'Email', 'Total', 'Status', 'Payment Method']);

            // Data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->user->name,
                    $order->user->email,
                    $order->total_amount,
                    $order->status,
                    $order->payment_method,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}