<?php

namespace App\Http\Controllers;

use App\Models\VendorAnalytics;
use App\Models\ProductAnalytics;
use App\Models\PlatformAnalytics;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    // Get vendor analytics
    public function getVendorAnalytics(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $vendor = $user->vendor;
        $timeframe = $request->get('timeframe', '30d'); // 7d, 30d, 90d, 1y

        $analytics = VendorAnalytics::where('vendor_id', $vendor->id)
            ->where('date', '>=', $this->getStartDate($timeframe))
            ->orderBy('date')
            ->get();

        // Summary statistics
        $summary = [
            'total_views' => $analytics->sum('views'),
            'total_clicks' => $analytics->sum('clicks'),
            'total_orders' => $analytics->sum('orders_count'),
            'total_revenue' => $analytics->sum('orders_total'),
            'conversion_rate' => $analytics->sum('views') > 0 ?
                ($analytics->sum('orders_count') / $analytics->sum('views')) * 100 : 0,
            'average_order_value' => $analytics->sum('orders_count') > 0 ?
                $analytics->sum('orders_total') / $analytics->sum('orders_count') : 0
        ];

        // Top products
        $topProducts = ProductAnalytics::whereHas('product', function($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            })
            ->select('product_id', DB::raw('SUM(views) as total_views'), DB::raw('SUM(orders_count) as total_orders'))
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('total_orders')
            ->limit(10)
            ->get();

        // Order status distribution
        $orderStatuses = Order::where('vendor_id', $vendor->id)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'timeframe' => $timeframe,
                'summary' => $summary,
                'daily_data' => $analytics,
                'top_products' => $topProducts,
                'order_statuses' => $orderStatuses
            ]
        ]);
    }

    // Get platform analytics (admin only)
    public function getPlatformAnalytics(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $timeframe = $request->get('timeframe', '30d');

        $analytics = PlatformAnalytics::where('date', '>=', $this->getStartDate($timeframe))
            ->orderBy('date')
            ->get();

        // Summary statistics
        $summary = [
            'total_users' => User::count(),
            'total_vendors' => Vendor::count(),
            'total_orders' => $analytics->sum('orders_count'),
            'total_revenue' => $analytics->sum('orders_total'),
            'total_commission' => $analytics->sum('commission_total'),
            'new_users' => $analytics->sum('new_users'),
            'new_vendors' => $analytics->sum('new_vendors'),
            'average_order_value' => $analytics->sum('orders_count') > 0 ?
                $analytics->sum('orders_total') / $analytics->sum('orders_count') : 0
        ];

        // Top vendors by orders
        $topVendors = VendorAnalytics::where('date', '>=', $this->getStartDate($timeframe))
            ->select('vendor_id', DB::raw('SUM(orders_count) as total_orders'), DB::raw('SUM(orders_total) as total_revenue'))
            ->groupBy('vendor_id')
            ->with('vendor')
            ->orderByDesc('total_orders')
            ->limit(10)
            ->get();

        // Revenue trend
        $revenueTrend = $analytics->groupBy(function($item) {
            return $item->date->format('Y-m');
        })->map(function($group) {
            return $group->sum('orders_total');
        });

        return response()->json([
            'success' => true,
            'data' => [
                'timeframe' => $timeframe,
                'summary' => $summary,
                'daily_data' => $analytics,
                'top_vendors' => $topVendors,
                'revenue_trend' => $revenueTrend
            ]
        ]);
    }

    private function getStartDate($timeframe)
    {
        return match($timeframe) {
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            '90d' => now()->subDays(90),
            '1y' => now()->subYear(),
            default => now()->subDays(30),
        };
    }

    // Record product view
    public function recordProductView($productId)
    {
        $today = now()->toDateString();

        ProductAnalytics::updateOrCreate(
            [
                'product_id' => $productId,
                'date' => $today
            ],
            [
                'views' => DB::raw('views + 1')
            ]
        );

        return response()->json(['success' => true]);
    }

    // Record vendor view
    public function recordVendorView($vendorId)
    {
        $today = now()->toDateString();

        VendorAnalytics::updateOrCreate(
            [
                'vendor_id' => $vendorId,
                'date' => $today
            ],
            [
                'views' => DB::raw('views + 1')
            ]
        );

        return response()->json(['success' => true]);
    }
}
