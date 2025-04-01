<?php

namespace App\Livewire\Seos;

use App\Models\Seo;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class EditSeo extends Component
{
    // EditSeo.php
    public Seo $seo;
    public array $translations = [
        'nl' => '',
        'fr' => '',
        'en' => ''
    ];

    protected $rules = [
        'translations.nl' => 'required',
        'translations.fr' => 'required',
        'translations.en' => 'required',
    ];

    public function mount(Seo $seo)
    {
        $this->seo = $seo;
        $this->translations['nl'] = $seo->nl;
        $this->translations['fr'] = $seo->fr;
        $this->translations['en'] = $seo->en;
    }

    public function save()
    {
        $this->validate();

        $this->seo->nl = $this->translations['nl'];
        $this->seo->fr = $this->translations['fr'];
        $this->seo->en = $this->translations['en'];
        $this->seo->save();

        session()->flash('message', __('SEO field successfully updated.'));
        session()->flash('message_type', 'success');

        return redirect()->route('seos.index');
    }

    public function render()
    {
        return view('livewire.seos.edit-seo');
    }
}
