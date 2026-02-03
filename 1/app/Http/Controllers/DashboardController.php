<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Delivery;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }
        
        if ($user->role === 'vendor') {
            return $this->vendorDashboard($user);
        }
        
        return $this->customerDashboard($user);
    }

    // public function index()
    // {
    //     $stats = [
    //         'total_users' => User::count(),
    //         'total_orders' => Order::count(),
    //         'total_products' => Product::count(),
    //         'total_revenue' => Order::sum('total'),
    //         'order_status' => [
    //             'completed' => Order::where('status', 'completed')->count(),
    //             'processing' => Order::where('status', 'processing')->count(),
    //             'pending' => Order::where('status', 'pending')->count(),
    //             'cancelled' => Order::where('status', 'cancelled')->count(),
    //         ]
    //     ];

    //     $recent_orders = Order::with(['user', 'vendor'])
    //         ->latest()
    //         ->take(10)
    //         ->get();

    //     return view('dashboard.index', [
    //         'stats' => $stats,
    //         'recent_orders' => $recent_orders
    //     ]);
    // }

    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('dashboard.users', compact('users'));
    }

    // public function vendors()
    // {
    //     $vendors = Vendor::with('user')->latest()->paginate(10);
    //     return view('dashboard.vendors', compact('vendors'));
    // }

    // public function distributors()
    // {
    //     $distributors = Distributor::latest()->paginate(10);
    //     return view('dashboard.distributors', compact('distributors'));
    // }

   

   


    public function orders()
    {
        $orders = Order::with(['user', 'vendor', 'delivery', 'items.product'])
            ->latest()
            ->paginate(10);
        return view('dashboard.orders', compact('orders'));
    }

    public function delivery()
    {
        $user = Auth::user();

        if ($user->role === 'vendor') {
            $query = Delivery::with(['order.user', 'order.vendor', 'deliveryPerson'])
            ->where('vendor_id', $user->id)
            ->latest();
        } elseif ($user->role === 'delivery') {
            $query = Delivery::with(['order.user', 'order.vendor', 'deliveryPerson'])
            ->where('delivery_person_id', $user->id)
            ->latest();
        } elseif ($user->role === 'admin') {
            $query = Delivery::with(['order.user', 'order.vendor', 'deliveryPerson'])
            ->latest();
        }

        if ($user->role === 'vendor') {
            $vendor = Vendor::where('user_id', $user->id)->first();
            if ($vendor) {
                $query->whereHas('order', function ($q) use ($vendor) {
                    $q->where('vendor_id', $vendor->id);
                });
            } else {
                $query->whereRaw('1=0');
            }
        } elseif ($user->role === 'delivery') {
            $query->where('delivery_person_id', $user->id);
        }

        $deliveries = $query->paginate(10);

        $deliveryPersons = User::where('role', 'delivery')->get();

        if ($user->role === 'admin') {
            return view('dashboard.delivery', compact('deliveries', 'deliveryPersons'));
        } else {
            return view('vendor.delivery', compact('deliveries', 'deliveryPersons'));
        }
    }


    /**
     * Admin: Update order status and notify customer via email
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,preparing,ready,on_delivery,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->status = $validated['status'];

        // If delivered, mark payment as paid when applicable
        if ($order->status === 'delivered' && $order->payment_status === 'pending') {
            $order->payment_status = 'paid';
        }

        $order->save();

        // Notify customer
        try {
            if ($order->user && $order->user->email) {
                Mail::to($order->user->email)->send(new OrderStatusUpdated($order, $oldStatus));
            }
        } catch (\Throwable $e) {
            // Log but don't break the flow
            report($e);
        }

        return back()->with('success', 'Order status updated to ' . ucfirst($order->status));
    }

    /**
     * Show admin dashboard with platform-wide statistics
     */
    protected function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_vendors' => Vendor::where('is_approved', true)->count(),
            'pending_vendors' => Vendor::where('is_approved', false)->count(),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_deliveries' => Delivery::count(),
            'total_distributors' => Distributor::count(),
            'recent_orders' => Order::with(['user', 'vendor'])
                ->latest()
                ->take(5)
                ->get(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_vendors' => Vendor::with('user')
                ->latest()
                ->take(5)
                ->get(),
            'revenue' => [
                'today' => Order::whereDate('created_at', today())->sum('total'),
                'week' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total'),
                'month' => Order::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('total'),
            ],
            'total_revenue' => Order::sum('total'),
            'order_status' => [
                'pending' => Order::where('status', 'pending')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'shipped' => Order::where('status', 'shipped')->count(),
                'delivered' => Order::where('status', 'delivered')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
            ]
        ];

        $recent_orders = Order::with(['user', 'vendor'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('dashboard.index', compact('stats', 'recent_orders'));
    }
    
    /**
     * Show vendor dashboard with vendor-specific statistics
     */
    protected function vendorDashboard($user)
    {
        $vendor = Vendor::where('user_id', $user->id)->first();
        $products_count = Product::where('vendor_id', $vendor->id)->count();
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('warning', 'Please complete your vendor profile to access the dashboard.');
        }
        
        $stats = [
            'total_orders' => $vendor->orders()->count(),
            'pending_orders' => $vendor->orders()->where('status', 'pending')->count(),
            'total_products' => $products_count,
            'total_revenue' => $vendor->orders()->sum('total'),
            'recent_orders' => $vendor->orders()
                ->with('user')
                ->latest()
                ->take(5)
                ->get(),
            'top_products' => $vendor->products()
                // ->withCount('orderItems')
                // ->orderBy('order_items_count', 'desc')
                ->take(5)
                ->get(),
            'revenue' => [
                'today' => $vendor->orders()
                    ->whereDate('created_at', today())
                    ->sum('total'),
                'week' => $vendor->orders()
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->sum('total'),
                'month' => $vendor->orders()
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('total'),
            ]
        ];
        
        return view('vendor.index', compact('stats', 'vendor'));
    }
    
    /**
     * Show customer dashboard with order history
     */
    protected function customerDashboard($user)
    {
        // $stats = [
        //     'total_orders' => $user->orders()->count(),
        //     'pending_orders' => $user->orders()->where('status', 'pending')->count(),
        //     'delivered_orders' => $user->orders()->where('status', 'delivered')->count(),
        //     'recent_orders' => $user->orders()
        //         ->with(['vendor', 'delivery'])
        //         ->latest()
        //         ->take(5)
        //         ->get(),
        //     'favorite_vendors' => $user->orders()
        //         ->with('vendor')
        //         ->select('vendor_id', DB::raw('count(*) as order_count'))
        //         ->groupBy('vendor_id')
        //         ->orderBy('order_count', 'desc')
        //         ->take(3)
        //         ->get()
        //         ->pluck('vendor')
        //         ->filter()
        // ];
        
        // return view('dashboard.customer', compact('stats'));
        return view('home.index');
    }
}
