<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Cuisine;
use App\Mail\VerifyEmail;
use Yoeunes\Toastr\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    // Display a listing of the vendors
    public function index()
    {
        $vendors = Vendor::with('user')->latest()->paginate(10);
        return view('dashboard.vendors.index', compact('vendors'));
    }

    // Show the form for creating a new vendor
    public function create()
    {
        $cuisines = Cuisine::all();
        return view('dashboard.vendors.create', compact('cuisines'));
    }

    // Store a newly created vendor in storage
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|string|max:20',
            'cuisine' => 'required|string|max:100',
            'description' => 'nullable|string',
            'location' => 'required|string|max:500',
            'image' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:2048',
            'min_order' => 'nullable|numeric|min:0',
            'free_delivery_over' => 'nullable|numeric|min:0',
            'opening_hours' => 'nullable|string',
            'delivery_time' => 'nullable|string|max:100',
            'delivery_fee' => 'nullable|numeric|min:0',
        ]);

        // Handle image uploads to public/uploads
        if ($request->hasFile('image')) {
            $dir = public_path('uploads/vendors');
            if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);
            $filename = uniqid().'_'.preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move($dir, $filename);
            $validated['image'] = 'vendors/'.$filename;
        }

        if ($request->hasFile('cover_image')) {
            $dir = public_path('uploads/vendors/cover');
            if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);
            $filename = uniqid().'_'.preg_replace('/\s+/', '_', $request->file('cover_image')->getClientOriginalName());
            $request->file('cover_image')->move($dir, $filename);
            $validated['cover_image'] = 'vendors/cover/'.$filename;
        }

        // Create a user for the vendor
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt('password'), // Default password, should be changed on first login
            'role' => 'vendor',
        ]);

        // Create vendor profile
        $validated['user_id'] = $user->id;
        $validated['verified'] = $request->has('verified');
        $validated['featured'] = $request->has('featured');
        $validated['is_approved'] = true; // Auto-approve admin-created vendors
        $validated['approved_at'] = now();

        Vendor::create($validated);

        notyf()->success('Vendor created successfully!');
        return redirect()->route('vendors.index');
    }

    // Display the specified vendor
    public function show(Vendor $vendor)
    {
        return view('dashboard.vendors.show', compact('vendor'));
    }

    // Show the form for editing the specified vendor
    public function edit(Vendor $vendor)
    {
        $cuisines = Cuisine::all();
        return view('dashboard.vendors.edit', compact('vendor', 'cuisines'));
    }

    // Update the specified vendor in storage
    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email,' . $vendor->id,
            'phone' => 'required|string|max:20',
            'cuisine' => 'required|string|max:100',
            'description' => 'nullable|string',
            'location' => 'required|string|max:500',
            'image' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:2048',
            'min_order' => 'nullable|numeric|min:0',
            'free_delivery_over' => 'nullable|numeric|min:0',
            'opening_hours' => 'nullable|string',
            'delivery_time' => 'nullable|string|max:100',
            'delivery_fee' => 'nullable|numeric|min:0',
        ]);

        // Handle image uploads in public/uploads and delete old
        if ($request->hasFile('image')) {
            if ($vendor->image && File::exists(public_path('uploads/' . $vendor->image))) {
                File::delete(public_path('uploads/' . $vendor->image));
            }
            $dir = public_path('uploads/vendors');
            if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);
            $filename = uniqid().'_'.preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move($dir, $filename);
            $validated['image'] = 'vendors/'.$filename;
        }

        if ($request->hasFile('cover_image')) {
            if ($vendor->cover_image && File::exists(public_path('uploads/' . $vendor->cover_image))) {
                File::delete(public_path('uploads/' . $vendor->cover_image));
            }
            $dir = public_path('uploads/vendors/cover');
            if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);
            $filename = uniqid().'_'.preg_replace('/\s+/', '_', $request->file('cover_image')->getClientOriginalName());
            $request->file('cover_image')->move($dir, $filename);
            $validated['cover_image'] = 'vendors/cover/'.$filename;
        }

        // Update user email if changed
        if ($vendor->user && $vendor->email !== $validated['email']) {
            $vendor->user->update(['email' => $validated['email']]);
        }

        $validated['verified'] = $request->has('verified');
        $validated['featured'] = $request->has('featured');
        $validated['is_approved'] = $request->has('is_approved');
        
        if ($request->has('is_approved') && !$vendor->approved_at) {
            $validated['approved_at'] = now();
        }

        $vendor->update($validated);

        notyf()->success('Vendor updated successfully!');
        return redirect()->route('vendors.index');
    }

    // Remove the specified vendor from storage
    public function destroy(Vendor $vendor)
    {
        // Delete images if they exist
        if ($vendor->image && File::exists(public_path('uploads/' . $vendor->image))) {
            File::delete(public_path('uploads/' . $vendor->image));
        }
        if ($vendor->cover_image && File::exists(public_path('uploads/' . $vendor->cover_image))) {
            File::delete(public_path('uploads/' . $vendor->cover_image));
        }

        // Delete associated user if exists
        if ($vendor->user) {
            $vendor->user->delete();
        }

        $vendor->delete();

        notyf()->success('Vendor deleted successfully!');
        return redirect()->route('vendors.index');
    }

    // Get vendor's products
    public function products($id)
    {
        $vendor = Vendor::findOrFail($id);
        $products = $vendor->products()->where('status', 'active')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    // Verify vendor (admin only)
public function verify($id)
{
    $vendor = Vendor::findOrFail($id);
    $vendor->update(['verified' => true]);

    return response()->json([
        'success' => true,
        'message' => 'Vendor verified successfully',
        'data' => $vendor
    ]);
}

// Feature vendor (admin only)
public function feature($id, Request $request)
{
    $vendor = Vendor::findOrFail($id);
    $vendor->update(['featured' => true]);

    

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Vendor featured successfully',
            'data' => $vendor
        ]);
    }

    notyf()->success('Vendor deleted successfully.');
    return redirect()->back();
}

public function approve($id)
{
    $vendor = Vendor::findOrFail($id);
    $vendor->update(['is_approved' => true]);

    notyf()->success('Vendor approved successfully.');
    return redirect()->back();
}

public function registerVendor(Request $request)
{
    // dd($request->all());

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'phone' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'postcode' => 'required|string|max:255',
        'location' => 'required|string|max:255',
    ]);

    $verify_code = rand(1000, 9999);
    

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
        'address' => $request->address,
        'postcode' => $request->postcode,
        'role' => 'vendor',
        'verify_code' => $verify_code,
    ]);

     $vendor = Vendor::create([
        'name' => $request->name,
        'user_id' => $user->id,
        'location' => $request->location,
        'postcode' => $request->postcode,
        'email' => $request->email,
        'phone' => $request->phone,
        'cuisine' => 'None',
        'description' => 'None',

        'min_order' => 0,
        'free_delivery_over' => 0,
        'opening_hours' => '09:00 - 18:00',
        'delivery_time' => '30 minutes',
        'delivery_fee' => 0,
    ]);

     event(new Registered($user));

     Auth::login($user);

     Mail::to($user->email)->send(new VerifyEmail('Verify Email', [
                    'user' => $user,
                    'verify_code' => $verify_code,
    ]));

    if($vendor){
        notyf()->success('Vendor registered successfully.');
        return redirect()->route('verify');
    }

    notyf()->error('Vendor registration failed.');
    return redirect()->back();
}
}
