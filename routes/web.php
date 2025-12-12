<?php

use App\Http\Controllers\ProfileController;
use App\Models\Partner;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\SiteSetting;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $settings = SiteSetting::first();
    $pillars = ProgramCategory::orderBy('sort_order')->get();
    $partners = Partner::orderBy('sort_order')->get();

    $heroSlides = Program::with(['category', 'media'])
        ->where('status', 'published')
        ->latest('published_at')
        ->take(5)
        ->get()
        ->map(function ($program) {
            return [
                'title' => $program->title,
                'subtitle' => $program->summary ?? '',
                'cta' => 'Dukung Program',
                'image' => $program->getFirstMediaUrl('cover') ?: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1600&q=80',
                'tag' => $program->category->name ?? 'Program',
                'url' => route('programs.show', $program->slug),
            ];
        })
        ->values();

    return view('index', compact('settings', 'pillars', 'partners', 'heroSlides'));
})->name('home');

Route::get('/programs', function (Request $request) {
    $settings = SiteSetting::first();
    $categories = ProgramCategory::orderBy('sort_order')->get();
    $partners = Partner::orderBy('sort_order')->get();

    $query = Program::with(['category', 'media'])
        ->where('status', 'published')
        ->latest('published_at');

    if ($request->filled('category')) {
        $query->whereHas('category', fn ($q) => $q->where('slug', $request->get('category')));
    }

    if ($request->filled('q')) {
        $q = $request->get('q');
        $query->where(function ($builder) use ($q) {
            $builder->where('title', 'like', "%{$q}%")
                ->orWhere('summary', 'like', "%{$q}%")
                ->orWhere('location', 'like', "%{$q}%");
        });
    }

    $programs = $query->paginate(9)->withQueryString();
    $filters = [
        'category' => $request->get('category', ''),
        'q' => $request->get('q', ''),
    ];

    return view('programs', compact('settings', 'categories', 'programs', 'partners', 'filters'));
})->name('programs');

Route::get('/programs/{slug}', function (string $slug) {
    $program = Program::with(['category', 'media'])->where('slug', $slug)->firstOrFail();
    $settings = SiteSetting::first();

    return view('program-show', compact('program', 'settings'));
})->name('programs.show');

Route::get('/about', function () {
    $settings = SiteSetting::first();
    return view('about', compact('settings'));
})->name('about');

Route::get('/dashboard', function () {
    $user = auth()->user();

    $donations = Donation::with('program')
        ->where('user_id', $user->id)
        ->latest()
        ->take(5)
        ->get();

    $totalConfirmed = Donation::where('user_id', $user->id)
        ->where('status', 'confirmed')
        ->sum('amount');

    $programSupported = Donation::where('user_id', $user->id)
        ->where('status', 'confirmed')
        ->distinct('program_id')
        ->count('program_id');

    $pendingCount = Donation::where('user_id', $user->id)
        ->where('status', 'pending')
        ->count();

    $recommendedPrograms = Program::with('media')
        ->where('status', 'published')
        ->latest('published_at')
        ->take(3)
        ->get();

    $stats = [
        ['label' => 'Total Donasi', 'value' => $totalConfirmed, 'desc' => 'Akumulasi donasi sukses'],
        ['label' => 'Program Didukung', 'value' => $programSupported, 'desc' => 'Program yang telah kamu bantu'],
        ['label' => 'Pembayaran Pending', 'value' => $pendingCount, 'desc' => 'Menunggu verifikasi'],
    ];

    return view('dashboard', compact('donations', 'stats', 'recommendedPrograms'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\DonationController;

Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
Route::get('/donations/{donation}/thank-you', [DonationController::class, 'thankyou'])->name('donations.thankyou');
