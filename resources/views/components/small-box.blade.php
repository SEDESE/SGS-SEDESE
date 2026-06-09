@props([
    'color' => 'bg-info',
    'icon'  => 'fas fa-circle',
    'label' => '',
    'value' => null,
    'link'  => null,
])

<div class="small-box {{ $color }}">
    <div class="inner">
        @if($value !== null)
            <h3>{{ $value }}</h3>
        @endif

        {{-- Slot para cards com conteúdo personalizado (listas, tabelas) --}}
        {{ $slot }}

        <p>{{ $label }}</p>
    </div>

    <div class="icon" aria-hidden="true">
        <i class="{{ $icon }}"></i>
    </div>

    @if($link)
    <a href="{{ $link }}" class="small-box-footer">
        Mais informações&nbsp;<i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
    </a>
    @endif
</div>
