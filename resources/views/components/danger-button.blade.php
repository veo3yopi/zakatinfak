<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-maroon border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-maroonDark active:bg-brand-maroon focus:outline-none focus:ring-2 focus:ring-brand-maroon focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
