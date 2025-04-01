<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CreatePost extends Component
{
    public $currentLanguage = 'en';
    public $title_en = '';
    public $title_nl = '';
    public $title_fr = '';
    public $slug_en = '';
    public $slug_nl = '';
    public $slug_fr = '';
    public $content_en = '';
    public $content_nl = '';
    public $content_fr = '';
    public $is_published = false;
    public $selectedCategories = [];
    public $allCategories = [];

    protected $rules = [
        'title_en' => 'required|min:3',
        'title_nl' => 'required|min:3',
        'title_fr' => 'required|min:3',
        'content_en' => 'required|min:10',
        'content_nl' => 'required|min:10',
        'content_fr' => 'required|min:10',
        'is_published' => 'boolean',
        'selectedCategories' => 'array',
    ];

    protected $messages=[
        'title_en.required' => 'The English title is required.',
        'title_en.min' => 'The English title must be at least 3 characters long.',
        'title_nl.required' => 'The Dutch title is required.',
        'title_nl.min' => 'The Dutch title must be at least 3 characters long.',
        'title_fr.required' => 'The French title is required.',
        'title_fr.min' => 'The French title must be at least 3 characters long.',
        'content_en.required' => 'The English content is required.',
        'content_en.min' => 'The English content must be at least 10 characters long.',
        'content_nl.required' => 'The Dutch content is required.',
        'content_nl.min' => 'The Dutch content must be at least 10 characters long.',
        'content_fr.required' => 'The French content is required.',
        'content_fr.min' => 'The French content must be at least 10 characters long.',
        'is_published.boolean' => 'The published status must be true or false.',
        'selectedCategories.array' => 'The selected categories must be an array.',
    ];

    public function mount()
    {
        $this->allCategories = Category::all();
        $this->currentLanguage=LaravelLocalization::getCurrentLocale();
    }

    public function changeCurrentLanguage($lang)
    {
        $this->currentLanguage = $lang;
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
        $this->slug_en = $this->generateUniqueSlug($this->title_en, 'slug_en');
        $this->slug_nl = $this->generateUniqueSlug($this->title_nl, 'slug_nl');
        $this->slug_fr = $this->generateUniqueSlug($this->title_fr, 'slug_fr');

        $this->validate();

        $post = Post::create([
            'author_id' => Auth::id(),
            'title_en' => $this->title_en,
            'title_nl' => $this->title_nl,
            'title_fr' => $this->title_fr,
            'slug_en' => $this->slug_en,
            'slug_nl' => $this->slug_nl,
            'slug_fr' => $this->slug_fr,
            'content_en' => $this->content_en,
            'content_nl' => $this->content_nl,
            'content_fr' => $this->content_fr,
            'is_published' => $this->is_published,
        ]);

        $post->categories()->sync($this->selectedCategories);

        session()->flash('message', 'Post succesvol aangemaakt.');
        session()->flash('message_type', 'success');

        return redirect()->route('posts.index');
    }

    private function generateUniqueSlug($title, $column)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (Post::where($column, $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function render()
    {
        return view('livewire.posts.create-posts', [
            'allCategories' => $this->allCategories,
        ]);
    }
}
