<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use App\Models\Category;

class CategoriesComposer 
{
    public function compose(View $view) {
        $categories = Category::get();
        $view->with('categories', $categories);
    }
}