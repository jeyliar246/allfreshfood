<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Cuisine;
use App\Models\Product;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index()
    {
        $featuredVendors = Vendor::where('is_approved', true)
            ->with(['products' => function ($query) {
                $query->take(3);
            }])
            ->inRandomOrder()
            ->take(9)
            ->get();

        $popularCuisines = Cuisine::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(8)
            ->get();

        $recentProducts = Product::with('vendor')
            ->whereHas('vendor', function ($query) {
                $query->where('is_approved', true);
            })
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('home.index', [
            'featuredVendors' => $featuredVendors,
            'popularCuisines' => $popularCuisines,
            'recentProducts' => $recentProducts
        ]);
    }

    public function vendors(Request $request)
    {
        $query = Vendor::query()
            ->where('is_approved', true)
            ->with(['products' => function ($q) {
                $q->take(3);
            }]);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('cuisine')) {
            $query->where('cuisine', $request->get('cuisine'));
        }

        $vendors = $query->paginate(12)->withQueryString();

        $cuisines = Cuisine::all();

        return view('home.vendors', [
            'vendors' => $vendors,
            'cuisines' => $cuisines
        ]);
    }

    public function vending()
    {
        return view('auth.vending');
    }

    public function help()
    {
        return view('home.help');
    }

    public function contact()
    {
        return view('home.contact');
    }

    public function privacy()
    {
        return view('home.privacy');
    }

    /**
     * Show a single vendor page with the vendor's products.
     */
    public function vendor($id, Request $request)
    {
        $vendor = Vendor::findOrFail($id);

        // Ensure only approved vendors are visible
        if (!$vendor->is_approved) {
            abort(404);
        }

        $productsQuery = Product::with('vendor')
            ->where('vendor_id', $vendor->id);

        if ($request->filled('search')) {
            $productsQuery->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $products = $productsQuery->paginate(12)->withQueryString();

        return view('home.vendor', [
            'vendor' => $vendor,
            'products' => $products,
        ]);
    }

    public function orders()
    {
        return view('home.orders');
    }

    public function cuisine()
    {
        $popularCuisines = Cuisine::withCount('products')
            ->orderBy('products_count', 'desc')
            ->get();

        return view('home.cuisines', compact('popularCuisines'));
    }

    public function profile()
    {
        return view('home.profile');
    }

    // public function browse(Request $request)
    // {
    //     $query = Product::query()->with('vendor', 'category')
    //         ->whereHas('vendor', function($q) {
    //             $q->where('is_approved', true);
    //         });

    //     // Search by postcode
    //     if ($request->filled('postcode')) {
    //         $postcode = trim($request->postcode);

    //         // Filter products by vendors in the same postcode area
    //         // This assumes vendor location contains postcode information
    //         $query->whereHas('vendor', function($q) use ($postcode) {
    //             // Extract postcode area (e.g., "SW1A" from "SW1A 1AA")
    //             $postcodeArea = strtoupper(preg_replace('/\s+/', '', substr($postcode, 0, 4)));

    //             // Match vendors with similar postcode area
    //             $q->where('is_approved', true)
    //             ->where(function($subQ) use ($postcode, $postcodeArea) {
    //                 // Exact match
    //                 $subQ->where('location', 'like', '%' . $postcode . '%')
    //                     // Or match postcode area (first 3-4 characters)
    //                     ->orWhere('location', 'like', '%' . $postcodeArea . '%');
    //             });
    //         });
    //     }

    //     // Search by product name or description
    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->where(function($q) use ($search) {
    //             $q->where('name', 'like', '%' . $search . '%')
    //             ->orWhere('description', 'like', '%' . $search . '%');
    //         });
    //     }

    //     // Filter by cuisine
    //     if ($request->filled('cuisine') && $request->cuisine) {
    //         $query->where('cuisine', $request->cuisine);
    //     }

    //     // Filter by category
    //     if ($request->filled('category') && $request->category) {
    //         $query->where('category_id', $request->category);
    //     }

    //     // Filter by vendor
    //     if ($request->filled('vendor') && $request->vendor) {
    //         $query->where('vendor_id', $request->vendor);
    //     }

    //     // Dietary/attributes flags
    //     foreach (['halal', 'vegan', 'gluten_free', 'organic', 'non_GMO'] as $flag) {
    //         if ($request->boolean($flag)) {
    //             $query->where($flag, true);
    //         }
    //     }

    //     // Price range
    //     if ($request->filled('min_price')) {
    //         $query->where('price', '>=', (float) $request->min_price);
    //     }
    //     if ($request->filled('max_price')) {
    //         $query->where('price', '<=', (float) $request->max_price);
    //     }

    //     // Sorting
    //     switch ($request->get('sort')) {
    //         case 'price_asc':
    //             $query->orderBy('price', 'asc');
    //             break;
    //         case 'price_desc':
    //             $query->orderBy('price', 'desc');
    //             break;
    //         case 'newest':
    //             $query->orderBy('created_at', 'desc');
    //             break;
    //         default:
    //             // Relevance fallback: random within constraints
    //             $query->inRandomOrder();
    //     }

    //     $products = $query->paginate(16)->withQueryString();
    //     $cuisines = Cuisine::all();
    //     $categories = \App\Models\Category::orderBy('name')->get();
    //     $vendors = \App\Models\Vendor::where('is_approved', true)->orderBy('name')->get();

    //     return view('home.browse', [
    //         'products' => $products,
    //         'cuisines' => $cuisines,
    //         'categories' => $categories,
    //         'vendors' => $vendors,
    //         'searchPostcode' => $request->postcode ?? null,
    //     ]);
    // }

    public function browse(Request $request)
    {
        $query = Product::query()
            ->with('vendor', 'category')
            ->whereHas('vendor', function ($q) {
                $q->where('is_approved', true);
            });

        // 🔍 Search by postcode or area (vendor.postcode or vendor.location)
        if ($request->filled('postcode')) {
            $searchInput = trim($request->postcode);
            $normalizedInput = strtoupper(preg_replace('/\s+/', '', $searchInput)); // normalize e.g. "SW1A 1AA" → "SW1A1AA"
            $postcodeArea = substr($normalizedInput, 0, 4); // take first 3–4 chars for broader match

            $query->whereHas('vendor', function ($vendorQuery) use ($searchInput, $normalizedInput, $postcodeArea) {
                $vendorQuery->where('is_approved', true)
                    ->where(function ($q) use ($searchInput, $normalizedInput, $postcodeArea) {
                        $q->where('postcode', 'like', "%{$searchInput}%")      // exact or partial postcode
                            ->orWhere('postcode', 'like', "%{$postcodeArea}%")   // area-based postcode
                            ->orWhere('location', 'like', "%{$searchInput}%");   // area/city name (e.g. London)
                    });
            });
        }

        // 🔍 Search by product name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 🔍 Filter by cuisine
        if ($request->filled('cuisine')) {
            $query->where('cuisine', $request->cuisine);
        }

        // 🔍 Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // 🔍 Filter by vendor
        if ($request->filled('vendor')) {
            $query->where('vendor_id', $request->vendor);
        }

        // ⚙️ Dietary/attribute filters
        foreach (['halal', 'vegan', 'gluten_free', 'organic', 'non_GMO'] as $flag) {
            if ($request->boolean($flag)) {
                $query->where($flag, true);
            }
        }

        // 💰 Price filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // ⚖️ Weight filters (parsed from description text)
        $hasWeightFilter = $request->filled('min_weight') || $request->filled('max_weight');
        if ($hasWeightFilter) {
            $minW = $request->filled('min_weight') ? (float) $request->min_weight : null;
            $maxW = $request->filled('max_weight') ? (float) $request->max_weight : null;

            // Fetch and filter in memory due to parsing requirement
            $all = $query->get();

            $filtered = $all->filter(function ($p) use ($minW, $maxW) {
                $w = $this->extractWeightKg((string)($p->description ?? ''));
                if ($w === null) return false; // only include products with detectable weight when filtering
                if ($minW !== null && $w < $minW) return false;
                if ($maxW !== null && $w > $maxW) return false;
                return true;
            });

            // Sorting on collection
            switch ($request->get('sort')) {
                case 'price_asc':
                    $filtered = $filtered->sortBy('price')->values();
                    break;
                case 'price_desc':
                    $filtered = $filtered->sortByDesc('price')->values();
                    break;
                case 'newest':
                    $filtered = $filtered->sortByDesc('created_at')->values();
                    break;
                default:
                    $filtered = $filtered->shuffle()->values();
            }

            // Manual pagination
            $perPage = 16;
            $currentPage = (int) max(1, (int) $request->get('page', 1));
            $items = $filtered->forPage($currentPage, $perPage)->values();
            $products = new LengthAwarePaginator(
                $items,
                $filtered->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            // 🔄 Sorting
            switch ($request->get('sort')) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->inRandomOrder();
            }

            // 📦 Fetch data
            $products = $query->paginate(16)->withQueryString();
        }
        $cuisines = \App\Models\Cuisine::all();
        $categories = \App\Models\Category::orderBy('name')->get();
        $vendors = \App\Models\Vendor::where('is_approved', true)->orderBy('name')->get();

        return view('home.browse', [
            'products' => $products,
            'cuisines' => $cuisines,
            'categories' => $categories,
            'vendors' => $vendors,
            'searchPostcode' => $request->postcode ?? null,
        ]);
    }

    private function extractWeightKg(string $text): ?float
    {
        // Find occurrences like "500g", "500 g", "0.5kg", "1 kg", "500 grams"
        $patterns = [
            '/(\d+(?:[\.,]\d+)?)\s*(kg|kilogram|kilograms)/i',
            '/(\d+(?:[\.,]\d+)?)\s*(g|gram|grams)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $m)) {
                $num = (float) str_replace(',', '.', $m[1]);
                $unit = strtolower($m[2]);
                if (in_array($unit, ['kg', 'kilogram', 'kilograms'])) {
                    return $num; // already kg
                }
                if (in_array($unit, ['g', 'gram', 'grams'])) {
                    return $num / 1000.0; // grams to kg
                }
            }
        }

        return null;
    }



    public function product(Product $product)
    {
        $product->load(['vendor', 'category']);
        // Related products: same vendor first; fallback to same category
        $relatedQuery = Product::query()->with('vendor')
            ->where('id', '!=', $product->id)
            ->whereHas('vendor', function ($q) {
                $q->where('is_approved', true);
            });
        if ($product->vendor_id) {
            $relatedQuery->where('vendor_id', $product->vendor_id);
        } elseif ($product->category_id) {
            $relatedQuery->where('category_id', $product->category_id);
        }
        $relatedProducts = $relatedQuery->take(8)->get();

        return view('home.product', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }



    public function verify()
    {
        return view('auth.verify');
    }

    public function verifyCode(Request $request)
    {

        $request->validate([
            'verify_code' => 'required',
        ]);

        $user = Auth::user();



        if ($user->verify_code != $request->verify_code) {
            notyf()->info('Invalid verification code.');
            return redirect()->route('verify');
        }


        $user->email_verified_at = now();
        $user->save();

        notyf()->info('Your email has been verified.');
        return redirect()->route('home');
    }

    public function resendVerifyCode()
    {
        $user = Auth::user();

        $verify_code = rand(1000, 9999);

        Mail::to($user->email)->send(new VerifyEmail('Verify Email', [
            'user' => $user,
            'verify_code' => $verify_code,
        ]));

        $user->verify_code = $verify_code;
        $user->save();

        notyf()->info('Verification code has been resent.');

        return redirect()->route('verify');
    }
}
