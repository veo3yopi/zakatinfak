<?php

use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredPrograms = Program::with('media')
        ->where('status', 'published')
        ->latest('published_at')
        ->take(5)
        ->get();

    $heroSlides = $featuredPrograms->map(function ($program) {
        $image = $program->getFirstMediaUrl('cover') ?: 'https://images.unsplash.com/photo-1455849318743-b2233052fcff?auto=format&fit=crop&w=1600&q=80';

        return [
            'title' => $program->title,
            'subtitle' => $program->summary ?? 'Dukung program kebaikan dengan transparansi dan laporan rutin.',
            'cta' => 'Dukung Program',
            'image' => $image,
            'tag' => $program->category?->name ?? 'Program',
            'url' => route('programs.show', $program->slug) . '#donasi',
        ];
    })->toArray();

    $settings = SiteSetting::first();

    return view('index', [
        'heroSlides' => $heroSlides,
        'settings' => $settings,
    ]);
});

Route::get('/programs', function (Request $request) {
    $categorySlug = $request->string('category')->toString();
    $search = $request->string('q')->toString();

    $query = Program::with(['category', 'media'])
        ->where('status', 'published');

    if ($categorySlug !== '') {
        $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    if ($search !== '') {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('summary', 'like', '%' . $search . '%')
                ->orWhere('location', 'like', '%' . $search . '%');
        });
    }

    $programs = $query
        ->orderByDesc('published_at')
        ->orderByDesc('created_at')
        ->paginate(9)
        ->withQueryString();

    $categories = ProgramCategory::orderBy('sort_order')->orderBy('name')->get();

    return view('programs', [
        'programs' => $programs,
        'categories' => $categories,
        'filters' => [
            'category' => $categorySlug,
            'q' => $search,
        ],
    ]);
})->name('programs');

Route::match(['get', 'post'], '/programs/{slug}', function (Request $request, string $slug) {
    $program = Program::with(['category', 'media'])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

    if ($request->isMethod('post')) {
        $validated = $request->validate([
            'donor_name' => ['required', 'string', 'max:255'],
            'donor_email' => ['nullable', 'email', 'max:255'],
            'amount' => ['required', 'numeric', 'min:10000'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        // Placeholder: simpan ke log atau integrasi payment gateway di masa depan.
        logger()->info('Donasi program', [
            'program_id' => $program->id,
            'program_title' => $program->title,
            'payload' => $validated,
        ]);

        return redirect()
            ->route('programs.show', $slug)
            ->with('status', 'Terima kasih, donasi kamu sudah dicatat. Kami akan menghubungi untuk langkah selanjutnya.');
    }

    return view('program-show', [
        'program' => $program,
    ]);
})->name('programs.show');
