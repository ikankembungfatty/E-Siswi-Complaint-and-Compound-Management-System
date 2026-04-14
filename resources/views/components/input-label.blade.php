@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label form-label-custom']) }}>
    {{ $value ?? $slot }}
</label>