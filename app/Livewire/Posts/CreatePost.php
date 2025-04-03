<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CreatePost extends Component
{
    public $currentLanguage = 'nl';
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

    protected $rules = [
        'title_en' => 'required|min:3',
        'title_nl' => 'required|min:3',
        'title_fr' => 'required|min:3',
        'title_es' => 'required|min:3',
        'content_en' => 'required|min:10',
        'content_nl' => 'required|min:10',
        'content_fr' => 'required|min:10',
        'content_es' => 'required|min:10',
        'is_published' => 'boolean',
        'selectedCategories' => 'array',
    ];

    public function mount()
    {
        $this->allCategories = Category::all();
        $this->currentLanguage = LaravelLocalization::getCurrentLocale();
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

    public function generateContent()
    {
        try {
            // Haal de titel op basis van de huidige taal (bv. title_nl, title_en etc.)
            $originalTitle = $this->{'title_' . $this->currentLanguage};

            // Controleer of er een titel aanwezig is
            if (empty($originalTitle)) {
                session()->flash('message', 'Vul eerst een titel in.');
                session()->flash('message_type', 'error');
                return;
            }

            // Constructie van het AI-promptsjabloon met dynamische titel
            $prompt = <<<EOT
            Je bent een meertalige blogschrijver. Genereer voor de volgende titel een korte blogbeschrijving en vertaal de titel en beschrijving naar Engels, Nederlands, Frans en Spaans.

            Titel: "$originalTitle"

            Geef het resultaat als geldig JSON-object in dit formaat:
            {
              "en": {"title": "...", "description": "..."},
              "nl": {"title": "...", "description": "..."},
              "fr": {"title": "...", "description": "..."},
              "es": {"title": "...", "description": "..."}
            }
            Alleen het JSON object. Geen uitleg of markdown.
            EOT;

            // Verstuur verzoek naar OpenAI API met gpt-4o-mini model
            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Je schrijft meertalige blogposts.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.5, // Lagere temperature voor meer voorspelbare output
                ]);

            // Verwerk de API-respons
            $content = trim($response->json('choices.0.message.content') ?? '');

            // Verwijder eventuele markdown codeblokken rond de JSON
            $content = preg_replace('/^```json|```$/i', '', $content);
            $content = trim($content);

            // Probeer de JSON te decoden
            $json = json_decode($content, true);

            // Valideer het JSON-resultaat
            if (!is_array($json)) {
                logger()->error('AI respons is geen geldig JSON', ['raw' => $content]);
                session()->flash('message', 'AI gaf geen geldig antwoord.');
                return;
            }

            // Update de modelproperties met de gegenereerde waarden
            // Fallback naar bestaande waarden als er iets misgaat
            $this->title_en = $json['en']['title'] ?? $this->title_en;
            $this->title_nl = $json['nl']['title'] ?? $this->title_nl;
            $this->title_fr = $json['fr']['title'] ?? $this->title_fr;
            $this->title_es = $json['es']['title'] ?? $this->title_es;

            $this->content_en = $json['en']['description'] ?? $this->content_en;
            $this->content_nl = $json['nl']['description'] ?? $this->content_nl;
            $this->content_fr = $json['fr']['description'] ?? $this->content_fr;
            $this->content_es = $json['es']['description'] ?? $this->content_es;

            session()->flash('message', 'Titels en beschrijvingen succesvol gegenereerd!');
        } catch (\Exception $e) {
            // Foutafhandeling: log de error en toon gebruikersmelding
            logger()->error('Fout bij generateContent(): ' . $e->getMessage());
            session()->flash('message', 'Er ging iets mis: ' . $e->getMessage());
            session()->flash('message_type', 'error');
        }
    }

    public function save()
    {
        $this->slug_en = $this->generateUniqueSlug($this->title_en, 'slug_en');
        $this->slug_nl = $this->generateUniqueSlug($this->title_nl, 'slug_nl');
        $this->slug_fr = $this->generateUniqueSlug($this->title_fr, 'slug_fr');
        $this->slug_es = $this->generateUniqueSlug($this->title_es, 'slug_es');

        $this->validate();

        $post = Post::create([
            'author_id' => Auth::id(),
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
