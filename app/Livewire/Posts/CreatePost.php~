<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreatePosts extends Component
{
    public $title = '';
    public $slug = '';
    public $content = '';
    public $is_published = false;
    public $selectedCategories = [];
    public $allCategories = [];

    protected $rules = [
        'title' => 'required|min:3',
        'slug' => 'required|min:3|unique:posts,slug',
        'content' => 'required|min:10',
        'is_published' => 'boolean',
        'selectedCategories' => 'array',
    ];

    public function mount()
    {
        $this->allCategories = Category::all();
    }


    public function toggleCategory($categoryId)
    {
        if (in_array($categoryId, $this->selectedCategories)) {
            $this->selectedCategories = array_diff($this->selectedCategories, [$categoryId]);
        } else {
            $this->selectedCategories[] = $categoryId;
        }
    }


    public function save()
    {

        $this->validate();

        $post = Post::create([
            'author_id' => Auth::id(),
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'is_published' => $this->is_published,
        ]);

        $post->categories()->sync($this->selectedCategories);

        session()->flash('message', 'Post succesvol aangemaakt.');
        session()->flash('message_type', 'success');

        return redirect()->route('posts.index');
    }

    public function render()
    {
        return view('livewire.posts.create-posts', [
            'allCategories' => $this->allCategories,
        ]);
    }
}
