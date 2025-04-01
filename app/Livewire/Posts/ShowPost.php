<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ShowPost extends Component
{
    public Post $post;
    public $currentLanguage = 'en';

    public function mount(Post $post){
        $this->currentLanguage=LaravelLocalization::getCurrentLocale();
        $this->post = $post;
    }

    public function render()
    {
        return view('livewire.posts.show-post');
    }
}
