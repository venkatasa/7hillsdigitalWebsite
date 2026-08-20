@props([
    'name',
    'label',
    'type' => 'text',
    'required' => false,
    'hint' => null,
    'placeholder' => null,
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-brand">
        {{ $label }}
        @unless ($required)
            <span class="font-normal text-muted">(optional)</span>
        @endunless
    </label>

    <input type="{{ $type }}"
           name="{{ $name }}"
           id="{{ $name }}"
           value="{{ old($name) }}"
           @required($required)
           @if ($placeholder) placeholder="{{ $placeholder }}" @endif
           @if ($errors->has($name)) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
           {{ $attributes->merge(['class' => 'mt-1.5 w-full rounded-lg border px-3.5 py-2.5 text-sm text-brand focus:border-accent focus:ring-0 '.($errors->has($name) ? 'border-red-400' : 'border-brand/20')]) }}>

    @if ($hint)
        <p class="mt-1.5 text-xs text-muted">{{ $hint }}</p>
    @endif

    @error($name)
        <p id="{{ $name }}-error" class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
