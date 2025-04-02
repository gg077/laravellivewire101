<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ShowPost extends Component
{
    public Post $post;
    public $currentLanguage = 'en';
    public $editing = false;
    public $title = '';
    public $content = '';

    protected $rules = [
        'title' => 'required|min:3',
        'content' => 'required|min:10',
    ];

    protected $messages = [
        'title.required' => 'The title field is required.',
        'title.min' => 'The title must be at least 3 characters.',
        'content.required' => 'The content field is required.',
        'content.min' => 'The content must be at least 10 characters.',
    ];

    public function mount(Post $post)
    {
        $this->currentLanguage = LaravelLocalization::getCurrentLocale();
        $this->post = $post;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->title = $this->post['title_'.$this->currentLanguage];
        $this->content = $this->post['content_'.$this->currentLanguage];
        $this->resetErrorBag();
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
        if (!$this->editing) {
            $this->resetFields();
        }
    }

    public function save()
    {
        $this->validate();

        $this->post->update([
            'title_'.$this->currentLanguage => $this->title,
            'content_'.$this->currentLanguage => $this->content,
        ]);

        $this->editing = false;
        session()->flash('message', __('Post successfully updated.'));
        session()->flash('message_type', 'success');
    }

    public function render()
    {
        return view('livewire.posts.show-post');
    }
}
