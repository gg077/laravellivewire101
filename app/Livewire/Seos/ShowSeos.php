<?php

namespace App\Livewire\Seos;

use App\Models\Seo;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ShowSeos extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.seos.show-seos', [
            'seos' => Seo::all()
        ]);
    }

// ShowSeos.php
    public function edit(Seo $seo)
    {
        return redirect()->route('seos.edit', $seo);
    }
}
