<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-light rounded-pill px-4']) }}>
    {{ $slot }}
</button>