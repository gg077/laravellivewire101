<?php

namespace App\Livewire\Posts;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class EditPost extends Component
{
    public Post $post;
    public $currentLanguage = 'en';
    public $title_en = '';
    public $title_nl = '';
    public $title_fr = '';
    public $title_es = '';
    public $slug_en = '';
    public $slug_nl = '';
    public $slug_fr = '';
    public $slug_es = '';
    public $content_en = '';
    public $content_nl = '';
    public $content_fr = '';
    public $content_es = '';
    public $is_published = false;
    public $selectedCategories = [];
    public $allCategories = [];

    public function rules() {
        return [
            'title_en' => 'required|min:3',
            'title_nl' => 'required|min:3',
            'title_fr' => 'required|min:3',
            'title_es' => 'required|min:3',
            'slug_en' => 'required|min:3|unique:posts,slug_en,'.$this->post->id,
            'slug_nl' => 'required|min:3|unique:posts,slug_nl,'.$this->post->id,
            'slug_fr' => 'required|min:3|unique:posts,slug_fr,'.$this->post->id,
            'slug_es' => 'required|min:3|unique:posts,slug_fr,'.$this->post->id,
            'content_en' => 'required|min:10',
            'content_nl' => 'required|min:10',
            'content_fr' => 'required|min:10',
            'content_es' => 'required|min:10',
            'is_published' => 'boolean',
            'selectedCategories' => 'array',
        ];
    }

    public function mount(Post $post) // mount om blade, vanuit database te laden
    {
        $this->currentLanguage=LaravelLocalization::getCurrentLocale();

        $this->post = $post;
        $this->title_en = $post->title_en;
        $this->title_nl = $post->title_nl;
        $this->title_fr = $post->title_fr;
        $this->title_es = $post->title_es;
        $this->slug_en = $post->slug_en;
        $this->slug_nl = $post->slug_nl;
        $this->slug_fr = $post->slug_fr;
        $this->slug_es = $post->slug_es;
        $this->content_en = $post->content_en;
        $this->content_nl = $post->content_nl;
        $this->content_fr = $post->content_fr;
        $this->content_es = $post->content_es;
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
            'title_es' => $this->title_es,
            'slug_en' => $this->slug_en,
            'slug_nl' => $this->slug_nl,
            'slug_fr' => $this->slug_fr,
            'slug_es' => $this->slug_es,
            'content_en' => $this->content_en,
            'content_nl' => $this->content_nl,
            'content_fr' => $this->content_fr,
            'content_es' => $this->content_es,
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
