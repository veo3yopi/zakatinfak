@php
    $settings = $settings ?? (class_exists(\App\Models\SiteSetting::class) ? \App\Models\SiteSetting::first() : null);

    $footerAboutText = $settings?->footer_about_text
        ?? 'Berkhidmat dalam pemberdayaan masyarakat melalui zakat, infak, sedekah, dan wakaf dengan pelaporan transparan serta audit berkala.';

    $footerLinks = collect($settings?->footer_links ?? [
        ['label' => 'FAQ', 'url' => '#'],
        ['label' => 'Laporan & Publikasi', 'url' => '#'],
        ['label' => 'Kebijakan Privasi', 'url' => '#'],
    ]);

    $footerSocials = collect($settings?->footer_social_links ?? [
        ['label' => 'Instagram', 'icon_path' => null, 'url' => '#'],
        ['label' => 'Facebook', 'icon_path' => null, 'url' => '#'],
        ['label' => 'YouTube', 'icon_path' => null, 'url' => '#'],
        ['label' => 'LinkedIn', 'icon_path' => null, 'url' => '#'],
    ]);

    $footerAddress = $settings?->footer_address ?? $settings?->address ?? 'Jl. Amanah No. 5, Jakarta Pusat 10430';
    $footerEmail = $settings?->footer_email ?? $settings?->contact_email ?? 'info@zakatimpact.org';
    $footerPhone = $settings?->footer_phone ?? $settings?->contact_phone ?? '+62 812-3456-7890';

    $footerLogo = $settings?->logo_url;
    if ($footerLogo && !\Illuminate\Support\Str::startsWith($footerLogo, ['http://', 'https://'])) {
        $footerLogo = \Illuminate\Support\Facades\Storage::url($footerLogo);
    }
@endphp

<footer id="site-footer" class="bg-slate-900 text-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-10 lg:grid-cols-4">
        <div class="space-y-4">
            <div class="flex items-center gap-2">
                <div class="h-11 w-11 rounded-2xl overflow-hidden bg-gradient-to-br from-brand-maroon to-brand-maroonDark flex items-center justify-center text-white font-semibold shadow-lg">
                    @if($footerLogo)
                        <img src="{{ $footerLogo }}" alt="{{ $settings->site_name ?? 'Logo' }}" class="h-full w-full object-cover">
                    @else
                        {{ strtoupper(\Illuminate\Support\Str::substr($settings->site_name ?? 'Z', 0, 1)) }}
                    @endif
                </div>
                <div>
                    <div class="text-lg font-semibold text-white">{{ $settings->site_name ?? 'Zakat Impact' }}</div>
                    <div class="text-sm text-slate-400">Lembaga zakat & infak amanah.</div>
                </div>
            </div>
            <p class="text-sm text-slate-400 leading-relaxed">
                {{ $footerAboutText }}
            </p>
        </div>
        <div class="space-y-3">
            <h4 class="text-lg font-semibold text-white">Kontak</h4>
            <p class="text-sm text-slate-400">{{ $footerAddress }}</p>
            <p class="text-sm text-slate-400">{{ $footerEmail }}</p>
            <p class="text-sm text-slate-400">{{ $footerPhone }}</p>
        </div>
        <div class="space-y-3">
            <h4 class="text-lg font-semibold text-white">Selengkapnya</h4>
            <ul class="space-y-2 text-sm text-slate-400">
                @foreach ($footerLinks as $link)
                    <li><a href="{{ $link['url'] ?? '#' }}" class="hover:text-white transition">{{ $link['label'] ?? 'Link' }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="space-y-3">
            <h4 class="text-lg font-semibold text-white">Media Sosial</h4>
            <div class="flex gap-3 flex-wrap">
                @foreach ($footerSocials as $social)
                    @php
                        $iconPath = $social['icon_path'] ?? null;
                        if ($iconPath && !\Illuminate\Support\Str::startsWith($iconPath, ['http://', 'https://'])) {
                            $iconPath = \Illuminate\Support\Facades\Storage::url($iconPath);
                        }
                        $iconFallback = strtoupper(\Illuminate\Support\Str::limit($social['label'] ?? 'S', 2, ''));
                    @endphp
                    <a href="{{ $social['url'] ?? '#' }}" class="flex h-11 w-11 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white hover:bg-white/20 transition overflow-hidden" aria-label="{{ $social['label'] ?? 'Sosial' }}">
                        @if($iconPath)
                            <img src="{{ $iconPath }}" alt="{{ $social['label'] ?? 'Sosial' }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-xs font-semibold tracking-wide">{{ $iconFallback }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="border-t border-white/10 py-5 text-center text-sm text-slate-500">
        Copyright © {{ now()->year }} {{ $settings->site_name ?? 'Zakat Impact' }}. Semua hak dilindungi.
    </div>
</footer>
