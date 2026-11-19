@props(['title', 'subtitle' => null, 'description' => null, 'align' => 'center', 'isLight' => false])

<div @class(['mb-12 max-w-3xl', 'mx-auto text-center' => $align === 'center'])>
    @if ($subtitle)
        <span @class([
            'mb-3 inline-block text-sm font-semibold uppercase tracking-widest',
            'text-orange-400' => $isLight,
            'text-orange-500' => ! $isLight,
        ])>{{ $subtitle }}</span>
    @endif
    <h2 @class([
        'mb-4 text-3xl font-bold tracking-tight md:text-4xl lg:text-5xl',
        'text-white' => $isLight,
        'text-gray-900' => ! $isLight,
    ])>{{ $title }}</h2>
    @if ($description)
        <p @class(['text-lg', 'text-gray-300' => $isLight, 'text-gray-600' => ! $isLight])>{{ $description }}</p>
    @endif
</div>
