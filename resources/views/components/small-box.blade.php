@props([
    'color' => 'bg-info',
    'icon'  => 'fas fa-circle',
    'label' => '',
    'value' => null,
    'link'  => null,
])

<div class="small-box {{ $color }}">

    {{-- Cabeçalho: ícone + label em destaque --}}
    <div class="small-box-label">
        <i class="{{ $icon }}" aria-hidden="true"></i>
        {{ $label }}
    </div>

    {{-- Conteúdo: rolável quando ultrapassar a altura fixa --}}
    <div class="inner">
        @if($value !== null)
            <h3>{{ $value }}</h3>
        @endif
        {{ $slot }}
    </div>

    {{-- Ícone decorativo de fundo --}}
    <div class="icon" aria-hidden="true">
        <i class="{{ $icon }}"></i>
    </div>

    @if($link)
    <a href="{{ $link }}" class="small-box-footer">
        Mais informações <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
    </a>
    @endif

</div>
