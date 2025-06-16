<?php

namespace App\Http\Controllers;

use App\Models\Variation;
use Illuminate\Http\Request;

class VariationController extends Controller
{
    public function updateName(Request $request, Variation $variation)
    {
        //
        $variation->name = $request->name;
        if ($variation->name == ''){
            $variation->name = ' ';
            $variation->update();
        }else{
            $variation->update();
        }
        return to_route('admin.variations');
    }

    public function updateComponent(Request $request, Variation $variation)
    {
        //
        $variation->component = $request->component;
        if ($variation->component == ''){
            $variation->component = ' ';
            $variation->update();
        }else{
            $variation->update();
        }
        return to_route('admin.variations');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $variation = new Variation();
        $variation->name = $request->name;
        $variation->component = $request->component;
        $variation->save();

        return to_route('admin.variations');
    }

    /**
     * Display the specified resource.
     */
    public function show(variation $variation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(variation $variation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, variation $variation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(variation $variation)
    {
        $variation->delete();
        return to_route('admin.variations');
    }
}
