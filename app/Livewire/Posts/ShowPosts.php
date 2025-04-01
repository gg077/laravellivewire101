<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ShowPosts extends Component
{
    use WithPagination;
    public $currentLanguage='en';

    public function mount(){
       $this->currentLanguage=LaravelLocalization::getCurrentLocale();
    }

    public function render()
    {
        return view('livewire.posts.show-posts', [
            'posts' => Post::latest()->paginate(10)
        ]);
    }
}
