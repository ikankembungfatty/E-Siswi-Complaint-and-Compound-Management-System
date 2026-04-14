<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary-custom']) }}>
    {{ $slot }}
</button>