<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditCategory extends Component
{
    public Category $category;
    public string $name = '';

    protected array $rules = [
        'name' => 'required|min:2|unique:categories,name',
    ];

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
    }

    public function save()
    {
        // Tijdelijk uitschakelen voor test
        // $user = Auth::user();
        // if (!$user->can('edit categories')) {
        //     abort(403);
        // }

        $this->rules['name'] = 'required|min:2|unique:categories,name,' . $this->category->id;

        $this->validate();

        $this->category->update([
            'name' => $this->name
        ]);

        session()->flash('message', __('Categorie succesvol bijgewerkt.'));
        session()->flash('message_type', 'success');

        return redirect()->route('categories.index');
    }


    public function render()
    {
        return view('livewire.categories.edit-category');
    }
}
