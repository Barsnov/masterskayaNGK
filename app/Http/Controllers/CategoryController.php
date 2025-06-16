<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;


class CategoryController extends Controller
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

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $category = new Category();
        $category->title = $request->name;
        $category->variation_id = $request->select;
        $category->save();

        if($request->file('files')){
            foreach ($request->file('files') as $imageFile) {
                $image = new Image();
                $path = $imageFile->store('images/category ' . $category->id);
                $image->name = $imageFile->getClientOriginalName();
                $image->url = $path;
                $image->category_id = $category->id;
                $image->save();
            }
        }

        return to_route('admin.categories');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateName(Request $request, Category $category)
    {
        //
        $category->title = $request->title;
        if ($category->title == ''){
            $category->title = ' ';
            $category->update();
        }else{
            $category->update();
        }
        return to_route('admin.categories');
    }

    public function updateVariation(Request $request, Category $category)
    {
        //
        $category->variation_id = $request->id;
        $category->update();
        return to_route('admin.categories');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        $category->delete();
        return to_route('admin.categories');
    }
}
