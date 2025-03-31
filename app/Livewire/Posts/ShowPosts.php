<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPosts extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.posts.show-posts', [
            'posts' => Post::latest()->paginate(10)
        ]);
    }
}
