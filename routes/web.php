<?php

use Illuminate\Support\Facades\Route;
use App\Models\Program;

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
            'url' => url('/programs'),
        ];
    })->toArray();

    return view('welcome', [
        'heroSlides' => $heroSlides,
    ]);
});

Route::get('/programs', function () {
    return view('programs');
})->name('programs');
