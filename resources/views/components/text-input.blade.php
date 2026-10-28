@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-sage-300 focus:border-botanic focus:ring-botanic/30 rounded-md shadow-xs text-stone-900']) }}>
