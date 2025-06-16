<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\VariationController;
use App\Models\Category;
use App\Models\Image;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Начальная страница
Route::get('/', function (){
    return Inertia::render('Welcome', ['categories'=>Category::all(), 'images'=>Image::all()]);
})->name('Welcome');

//Страница игры выбранной категории
Route::get('/app', function (Request $request){
    $categoryId = $request->category;
    $component = Variation::query()->where('id', (Category::query()->where('id', $categoryId)
        ->first()->variation_id))->first()->component;
    $images = Image::where('category_id', $categoryId)->get();

    return Inertia::render('AppComponents/App', ['images'=>$images, 'component'=>$component]);
})->name('App');

//Админ-Панелечка
Route::get('/admin', function (){
    return Inertia::render('AdminComponents/Admin', []);
});

//Страница с категориями из Админ-Панели
Route::get('/admin/categories', function (){
    return Inertia::render('AdminComponents/Components/Categories', ['categories'=>Category::all(), 'images'=>Image::all(), 'variations'=>Variation::all()]);
})->name('admin.categories');

Route::get('/admin/variations', function (){
    return Inertia::render('AdminComponents/Components/Variations', ['variations'=>Variation::all()]);
})->name('admin.variations');

//Добавление категории и создание картинок, если они добавлены были
Route::post('/admin/categories/category', [CategoryController::class, 'store'])->name('admin.categories.category.add');

//Добавление вариации
Route::post('/admin/variations/variation', [VariationController::class, 'store'])->name('admin.variations.variation.add');

//Создание картинок у выбранной категории, при изменении
Route::post('/admin/images/image', [ImageController::class, 'store'])->name('admin.images.image.add');

//Удаление категории
Route::delete('/admin/categories/category/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.category.destroy');

//Изменение названия категории
Route::put('/admin/categories/category/up/{category}', [CategoryController::class, 'updateName'])->name('admin.categories.category.update');

//Изменение вариации у категории
Route::put('/admin/categories/category/variation/up/{category}', [CategoryController::class, 'updateVariation'])->name('admin.categories.category.variation.update');

//Изменение ответа у Картинки
Route::put('/admin/images/image/up/{image}', [ImageController::class, 'update'])->name('admin.images.image.update');

//Удаление картинки из БД
Route::delete('/admin/images/image/{image}', [ImageController::class, 'destroy'])->name('admin.images.image.destroy');

//Изменение названия у вариации
Route::put('/admin/variations/variation/up/{variation}', [VariationController::class, 'updateName'])->name('admin.variations.variation.update');

//Изменение компонента у вариации
Route::put('/admin/variations/variation/component/up/{variation}', [VariationController::class, 'updateComponent'])->name('admin.variations.variation.component.update');

//Удаление вариации
Route::delete('/admin/variations/variation/delete/{variation}', [VariationController::class, 'destroy'])->name('admin.variations.variation.destroy');
