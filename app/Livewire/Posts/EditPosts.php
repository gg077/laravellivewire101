<?php

namespace App\Livewire\Posts;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Str;

class EditPosts extends Component
{
    public Post $post;
    public $title = '';
    public $slug = '';
    public $content = '';
    public $is_published = false;

    public $selectedCategories = [];
    public $allCategories = [];

    protected $rules = [
        'title' => 'required|min:3',
        'slug' => 'required|min:3',
        'content' => 'required|min:10',
        'is_published' => 'boolean', // hier is het 1 = gepubliceerd of 0 = niet gepubliceerd
        'selectedCategories' => 'array'
    ];

    public function mount(Post $post) // mount om blade, vanuit database te laden
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->content = $post->content;
        $this->is_published = $post->is_published;

        $this->selectedCategories = $post->categories()->pluck('categories.id')->toArray();
        $this->allCategories = Category::all();
    }



    public function toggleCategory($categoryId)
    {
        // Als de categorie al in de selectie zit, verwijder hem dan
        if (in_array($categoryId, $this->selectedCategories)) {
            // array_diff haalt de aangeklikte ID uit de array
            $this->selectedCategories = array_diff($this->selectedCategories, [$categoryId]);
        } else {
            // Als de categorie nog niet geselecteerd is, voeg hem toe
            $this->selectedCategories[] = $categoryId;
        }
    }

    public function save()
    {
        $this->validate();

        $this->post->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'is_published' => $this->is_published,
        ]);

        $this->post->categories()->sync($this->selectedCategories);

        session()->flash('message', __('Post succesvol bijgewerkt.'));
        session()->flash('message_type', 'success');

        return redirect()->route('posts.index');
    }

    public function render()
    {
        return view('livewire.posts.edit-posts', [
            'allCategories' => $this->allCategories,
        ]);
    }
}
