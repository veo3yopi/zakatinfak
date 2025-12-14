<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $settings = \App\Models\SiteSetting::first();
        $pillars = \App\Models\ProgramCategory::orderBy('sort_order')->get();
        $partners = \App\Models\Partner::orderBy('sort_order')->get();

        $heroSlides = \App\Models\Program::with(['category', 'media'])
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

        $posts = \App\Models\Post::with(['category', 'media'])
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

        $programs = \App\Models\Program::latest()->take(6)->get();
        return view('index', compact('settings', 'pillars', 'partners', 'heroSlides', 'posts', 'programs'));
    }
}
