<?php

namespace App\Livewire\Posts;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class EditPosts extends Component
{
    public Post $post;
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

    public function rules() {
        return [
            'title_en' => 'required|min:3',
            'title_nl' => 'required|min:3',
            'title_fr' => 'required|min:3',
            'slug_en' => 'required|min:3|unique:posts,slug_en,'.$this->post->id,
            'slug_nl' => 'required|min:3|unique:posts,slug_nl,'.$this->post->id,
            'slug_fr' => 'required|min:3|unique:posts,slug_fr,'.$this->post->id,
            'content_en' => 'required|min:10',
            'content_nl' => 'required|min:10',
            'content_fr' => 'required|min:10',
            'is_published' => 'boolean',
            'selectedCategories' => 'array',
        ];
    }

    protected $messages=[
        'title_en.required' => 'The English title is required.',
        'title_en.min' => 'The English title must be at least 3 characters long.',
        'title_nl.required' => 'The Dutch title is required.',
        'title_nl.min' => 'The Dutch title must be at least 3 characters long.',
        'title_fr.required' => 'The French title is required.',
        'title_fr.min' => 'The French title must be at least 3 characters long.',
        'slug_en.required' => 'The English slug is required.',
        'slug_en.min' => 'The English slug must be at least 3 characters.',
        'slug_en.unique' => 'The English slug must be unique.',
        'slug_nl.required' => 'The Dutch slug is required.',
        'slug_nl.min' => 'The Dutch slug must be at least 3 characters.',
        'slug_nl.unique' => 'The Dutch slug must be unique.',
        'slug_fr.required' => 'The French slug is required.',
        'slug_fr.min' => 'The French slug must be at least 3 characters.',
        'slug_fr.unique' => 'The French slug must be unique.',
        'content_en.required' => 'The English content is required.',
        'content_en.min' => 'The English content must be at least 10 characters long.',
        'content_nl.required' => 'The Dutch content is required.',
        'content_nl.min' => 'The Dutch content must be at least 10 characters long.',
        'content_fr.required' => 'The French content is required.',
        'content_fr.min' => 'The French content must be at least 10 characters long.',
        'is_published.boolean' => 'The published status must be true or false.',
        'selectedCategories.array' => 'The selected categories must be an array.',
    ];

    public function mount(Post $post) // mount om blade, vanuit database te laden
    {
        $this->currentLanguage=LaravelLocalization::getCurrentLocale();

        $this->post = $post;
        $this->title_en = $post->title_en;
        $this->title_nl = $post->title_nl;
        $this->title_fr = $post->title_fr;
        $this->slug_en = $post->slug_en;
        $this->slug_nl = $post->slug_nl;
        $this->slug_fr = $post->slug_fr;
        $this->content_en = $post->content_en;
        $this->content_nl = $post->content_nl;
        $this->content_fr = $post->content_fr;
        $this->is_published = $post->is_published;

        $this->selectedCategories = $post->categories()->pluck('categories.id')->toArray();
        $this->allCategories = Category::all();
    }

    public function changeCurrentLanguage($lang)
    {
        $this->currentLanguage = $lang;
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
