<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateCategory extends Component
{
    public string $name = '';

    protected array $rules = [
        'name' => 'required|min:2|unique:categories,name'
    ];

    public function save()
    {
        //$user = Auth::user();
        //if (!$user->can('create categories')) {
        //    abort(403);
       // }

        $this->validate();

        Category::create([
            'name' => $this->name
        ]);

        session()->flash('message', __('Categorie succesvol aangemaakt.'));
        session()->flash('message_type', 'success');

        return redirect()->route('categories.index');
    }

    public function render()
    {
        return view('livewire.categories.create-category');
    }
}
