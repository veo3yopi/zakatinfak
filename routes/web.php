<?php

use App\Http\Controllers\ProfileController;
use App\Models\Partner;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\SiteSetting;
use App\Models\Donation;
use App\Models\BankAccount;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
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

    $posts = Post::with(['category', 'media'])
        ->where('status', 'published')
        ->latest('published_at')
        ->take(3)
        ->get()
        ->map(function ($post) {
            return [
                'title' => $post->title,
                'excerpt' => $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 120),
                'date' => optional($post->published_at ?? $post->created_at)->format('d M Y'),
                'image' => $post->getFirstMediaUrl('cover') ?: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=900&q=80',
                'url' => route('posts.show', $post->slug),
            ];
        })
        ->values();

    return view('index', compact('settings', 'pillars', 'partners', 'heroSlides', 'posts'));
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

Route::get('/posts', function (Request $request) {
    $settings = SiteSetting::first();
    $categories = PostCategory::orderBy('sort_order')->get();
    $tags = Tag::orderBy('name')->get();

    $query = Post::with(['category', 'author', 'media', 'tags'])
        ->where('status', 'published')
        ->latest('published_at');

    if ($request->filled('category')) {
        $query->whereHas('category', fn ($q) => $q->where('slug', $request->get('category')));
    }

    if ($request->filled('tag')) {
        $query->whereHas('tags', fn ($q) => $q->where('slug', $request->get('tag')));
    }

    if ($request->filled('q')) {
        $q = $request->get('q');
        $query->where(function ($builder) use ($q) {
            $builder->where('title', 'like', "%{$q}%")
                ->orWhere('excerpt', 'like', "%{$q}%")
                ->orWhere('content', 'like', "%{$q}%");
        });
    }

    $posts = $query->paginate(9)->withQueryString();

    return view('posts', compact('settings', 'categories', 'tags', 'posts'));
})->name('posts.index');

Route::get('/posts/{slug}', function (string $slug) {
    $post = Post::with(['category', 'author', 'media', 'tags'])->where('slug', $slug)->where('status', 'published')->firstOrFail();
    $settings = SiteSetting::first();
    $related = Post::with(['category', 'media'])
        ->where('status', 'published')
        ->where('id', '!=', $post->id)
        ->where('post_category_id', $post->post_category_id)
        ->latest('published_at')
        ->take(3)
        ->get();

    return view('post-show', compact('post', 'settings', 'related'));
})->name('posts.show');

Route::get('/search', function (Request $request) {
    $settings = SiteSetting::first();
    $q = trim($request->get('q', ''));

    if (strlen($q) < 2) {
        return redirect()->back()->with('status', 'Masukkan minimal 2 karakter untuk pencarian.');
    }

    $programs = Program::with(['category', 'media'])
        ->where('status', 'published')
        ->where(function ($builder) use ($q) {
            $builder->where('title', 'like', "%{$q}%")
                ->orWhere('summary', 'like', "%{$q}%")
                ->orWhere('location', 'like', "%{$q}%")
                ->orWhereHas('category', fn ($cat) => $cat->where('name', 'like', "%{$q}%"));
        })
        ->latest('published_at')
        ->limit(12)
        ->get();

    $posts = Post::with(['category', 'media', 'tags'])
        ->where('status', 'published')
        ->where(function ($builder) use ($q) {
            $builder->where('title', 'like', "%{$q}%")
                ->orWhere('excerpt', 'like', "%{$q}%")
                ->orWhere('content', 'like', "%{$q}%")
                ->orWhereHas('category', fn ($cat) => $cat->where('name', 'like', "%{$q}%"))
                ->orWhereHas('tags', fn ($tag) => $tag->where('name', 'like', "%{$q}%"));
        })
        ->latest('published_at')
        ->limit(12)
        ->get();

    return view('search', compact('settings', 'q', 'programs', 'posts'));
})->name('search');

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

    $settings = SiteSetting::first();

    return view('dashboard', compact('donations', 'stats', 'recommendedPrograms', 'settings'));
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

Route::get('/donations/{donation}/payment', [DonationController::class, 'payment'])->name('donations.payment');
Route::post('/donations/{donation}/payment', [DonationController::class, 'uploadProof'])->name('donations.uploadProof');
