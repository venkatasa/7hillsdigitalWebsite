{{--
    Hardware procurement, kept deliberately quiet. It is a service we will
    happily provide, but it is not what we sell — giving it real estate on the
    pricing page makes us read as a reseller rather than a software company.
    One small line, no heading, no card. See config/brand.php.
--}}
<p {{ $attributes->merge(['class' => 'text-xs leading-relaxed text-muted']) }}>
    {{ config('brand.hardware.blurb') }}
    <a href="{{ route('contact') }}" class="underline underline-offset-2 hover:text-accent">Contact sales</a>.
</p>
