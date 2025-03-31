<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;


use Spatie\Permission\Models\Role;

class ShowCategory extends Component
{
    public function render()
    {
        return view('livewire.categories.show-category', [
            'categories' => Category::latest()->paginate(10)
        ]);
    }

}
