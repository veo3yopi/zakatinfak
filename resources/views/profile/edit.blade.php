<x-app-layout>
    <div class="bg-gradient-to-b from-brand-maroon/10 via-white to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col gap-6">
                <div class="rounded-3xl bg-white shadow-lg shadow-brand-maroon/20 p-6 sm:p-8 flex flex-col gap-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-600">Dashboard Donatur</p>
                            <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Profil & Keamanan Akun</h1>
                            <p class="text-slate-600">Perbarui identitas, email, kata sandi, dan penghapusan akun dengan aman.</p>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs font-semibold text-teal-600">Nama</p>
                            <p class="text-lg font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs font-semibold text-teal-600">Email</p>
                            <p class="text-lg font-semibold text-slate-900">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs font-semibold text-teal-600">Bergabung</p>
                            <p class="text-lg font-semibold text-slate-900">{{ auth()->user()->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-3xl bg-white shadow-lg shadow-slate-200/60 p-6 sm:p-8">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <div class="rounded-3xl bg-white shadow-lg shadow-slate-200/60 p-6 sm:p-8">
                        @include('profile.partials.update-password-form')
                    </div>

                    <div class="lg:col-span-2 rounded-3xl bg-white shadow-lg shadow-brand-maroon/20 p-6 sm:p-8">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
