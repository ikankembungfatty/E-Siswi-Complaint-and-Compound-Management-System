@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-custom-success small']) }}>
        {{ $status }}
    </div>
@endif