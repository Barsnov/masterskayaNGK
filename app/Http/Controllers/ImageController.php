<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
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
        //
        if($request->file('images')){
            foreach ($request->file('images') as $imageFile) {
                $image = new Image();
                $path = $imageFile->store('images/category ' . $request->category);
                $image->name = $imageFile->getClientOriginalName();
                $image->url = $path;
                $image->category_id = $request->category;
                $image->save();
            }
        }

        return to_route('admin.categories');
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        $image->answer = $request->answer;
        if($image->answer == ''){
            $image->answer = ' ';
            $image->update();
        }else{
            $image->update();
        }
        return to_route('admin.categories');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //
        $image->delete();
        return to_route('admin.categories');
    }
}
