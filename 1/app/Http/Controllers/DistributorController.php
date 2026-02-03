<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yoeunes\Toastr\Toastr;

class DistributorController extends Controller
{
    // Display a listing of the distributors
    public function index()
    {
        $distributors = Distributor::latest()->paginate(10);
        return view('dashboard.distributors.index', compact('distributors'));
    }

    // Show the form for creating a new distributor
    public function create()
    {
        return view('dashboard.distributors.create');
    }

    // Store a newly created distributor in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:distributors,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'contact_person' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            $dir = public_path('uploads/distributors');
            if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);
            $filename = uniqid().'_'.preg_replace('/\s+/', '_', $request->file('logo')->getClientOriginalName());
            $request->file('logo')->move($dir, $filename);
            $validated['logo'] = 'distributors/'.$filename;
        }

        $validated['status'] = $request->has('status');

        Distributor::create($validated);

        notyf()->success('Distributor created successfully!');
        return redirect()->route('distributors.index');
    }

    // Display the specified distributor
    public function show(Distributor $distributor)
    {
        return view('dashboard.distributors.show', compact('distributor'));
    }

    // Show the form for editing the specified distributor
    public function edit(Distributor $distributor)
    {
        return view('dashboard.distributors.edit', compact('distributor'));
    }

    // Update the specified distributor in storage
    public function update(Request $request, Distributor $distributor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:distributors,email,' . $distributor->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'contact_person' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($distributor->logo && File::exists(public_path('uploads/' . $distributor->logo))) {
                File::delete(public_path('uploads/' . $distributor->logo));
            }
            $dir = public_path('uploads/distributors');
            if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);
            $filename = uniqid().'_'.preg_replace('/\s+/', '_', $request->file('logo')->getClientOriginalName());
            $request->file('logo')->move($dir, $filename);
            $validated['logo'] = 'distributors/'.$filename;
        }

        $validated['status'] = $request->has('status');

        $distributor->update($validated);

        notyf()->success('Distributor updated successfully!');
        return redirect()->route('distributors.index');
    }

    // Remove the specified distributor from storage
    public function destroy(Distributor $distributor)
    {
        // Delete logo if exists
        if ($distributor->logo && File::exists(public_path('uploads/' . $distributor->logo))) {
            File::delete(public_path('uploads/' . $distributor->logo));
        }

        $distributor->delete();

        notyf()->success('Distributor deleted successfully!');
        return redirect()->route('distributors.index');
    }
}
