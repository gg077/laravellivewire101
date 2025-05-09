
## Meertalige Livewire CMS Implementatie

Dit document legt uit hoe we een meertalig CMS hebben geïmplementeerd met Laravel Livewire, waarmee administrators per taal (Nederlands, Engels, Frans) de titel en inhoud kunnen aanpassen.



### Implementatiestappen

#### 1. Databasestructuur

In plaats van een vertaaltabel te gebruiken, hebben we gekozen voor een implementatie waarbij elke taal zijn eigen kolom heeft in de database. Dit vereenvoudigt queries en maakt directe updates mogelijk.

Post model bevat de volgende vertaalde velden:
- `title_en`, `title_nl`, `title_fr` (titels per taal)
- `slug_en`, `slug_nl`, `slug_fr` (URL slugs per taal)
- `content_en`, `content_nl`, `content_fr` (inhoud per taal)

#### 2. Livewire Component Opzetten

We hebben een `CreatePost` Livewire component gemaakt die de meertalige invoer afhandelt:

```php
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
    
    // ...
}
```

#### 3. Validatieregels Instellen

De component valideert de invoer voor alle talenversies:

```php
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
    // ...meer foutmeldingen...
];
```

#### 4. Taal Wisselfunctionaliteit

We hebben een methode geïmplementeerd waarmee gebruikers kunnen wisselen tussen talen tijdens het bewerken:

```php
public function changeCurrentLanguage($lang)
{
    $this->currentLanguage = $lang;
}
```

In de view wordt deze functie aangeroepen met taalvlaggen:

```html
<div class="ml-auto flex rounded border border-gray-200 shadow-sm h-fit w-fit">
    <button type="button" class="flex p-1 h-fit cursor-pointer {{$title_en&&$content_en?'bg-green-100':'bg-red-100'}}" wire:click="changeCurrentLanguage('en')">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><!-- UK vlag --></svg>
    </button>
    <button type="button" class="flex p-1 h-fit cursor-pointer {{$title_nl&&$content_nl?'bg-green-100':'bg-red-100'}}" wire:click="changeCurrentLanguage('nl')">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><!-- NL vlag --></svg>
    </button>
    <button type="button" class="flex p-1 h-fit cursor-pointer {{$title_fr&&$content_fr?'bg-green-100':'bg-red-100'}}" wire:click="changeCurrentLanguage('fr')">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><!-- FR vlag --></svg>
    </button>
</div>
```

#### 5. Dynamische Formvelden op Basis van Taal

We gebruiken een `@switch` statement om verschillende formuliervelden weer te geven op basis van de geselecteerde taal:

```blade
@switch($currentLanguage)
    @case('en')
        <div class="col-span-5">
            <label for="title_en" class="flex text-sm font-medium text-gray-700 dark:text-gray-300">
                <svg><!-- UK vlag --></svg>
                Titel
            </label>
            <input type="text" wire:model="title_en" id="title_en" class="p-2 mt-1 block w-full dark:bg-gray-900 border-gray-300 dark:border-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <x-ui.forms.error error="title_en" />
        </div>
        @break
    @case('nl')
        <!-- Soortgelijke structuur voor Nederlands -->
        @break
    @case('fr')
        <!-- Soortgelijke structuur voor Frans -->
        @break
@endswitch
```

#### 6. Visuele Feedback voor Taalstatus

De taalschakelaars tonen een visuele indicator (groen/rood) om aan te geven of de vereiste velden voor die taal zijn ingevuld:

```html
<button type="button" class="flex p-1 h-fit cursor-pointer {{$title_en&&$content_en?'bg-green-100':'bg-red-100'}}" wire:click="changeCurrentLanguage('en')">
```

#### 7. Opslaan van Meertalige Content

Bij het opslaan worden unieke slugs gegenereerd voor elke taalversie en alle gegevens worden opgeslagen in de database:

```php
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
```

#### 8. Helper Methode voor Unieke Slugs

```php
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
```

## Conclusie

Deze implementatie stelt beheerders in staat om:
1. Content in meerdere talen (EN, NL, FR) toe te voegen en te bewerken
2. Eenvoudig tussen talen te schakelen tijdens het bewerken
3. Visuele feedback te krijgen over de status van elke taalversie
4. Alle vertalingen samen op te slaan als één post-entiteit

De code voldoet aan de opdracht door admins in staat te stellen per taal de titel en inhoud aan te passen, en meertalige inhoud op te slaan en te bewerken op een gebruiksvriendelijke manier.

qroirejfpoqkf
