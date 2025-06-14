@props(['secondary' => false])
<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center uppercase tracking-widest btn btn-sm text-white' . ($secondary ? ' btn-secondary' : ' btn-primary')]) }}>
    {{ $slot }}
</button>
