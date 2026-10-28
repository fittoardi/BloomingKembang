<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-botanic border border-transparent rounded-xs font-semibold text-xs text-white uppercase tracking-widest hover:bg-sage-700 focus:bg-sage-700 active:bg-sage-800 focus:outline-none focus:ring-2 focus:ring-botanic/30 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
