<?php

namespace App\Http\Controllers;

use App\Models\MarkUp;
use Illuminate\Http\Request;

class MarkUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $markups = MarkUp::all();
        return view('dashboard.markup.index', compact('markups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.markup.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'markup_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $markup = MarkUp::updateOrCreate(
            [], 
            ['markup_percentage' => $request->markup_percentage]
        );

        if ($markup->wasRecentlyCreated) {
            notyf()->success('Markup created successfully.');
        } else {
            notyf()->info('Markup updated successfully.');
        }
        
        return redirect()->route('markup');
    }

    /**
     * Display the specified resource.
     */
    public function show(MarkUp $markUp)
    {
        return view('dashboard.markup.show', compact('markUp'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MarkUp $markUp)
    {
        dd($markUp);
        
        return view('dashboard.markup.edit', compact('markUp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MarkUp $markUp)
    {
        $request->validate([
            'markup_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $markUp->update($request->only('markup_percentage'));

        notyf()->success('Markup updated successfully.');
        return redirect()->route('markup');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MarkUp $markUp)
    {
        $markUp->delete();
        notyf()->success('Markup deleted successfully.');
        return redirect()->route('markup');
    }
}
