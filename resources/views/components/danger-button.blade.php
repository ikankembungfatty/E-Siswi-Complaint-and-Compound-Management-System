<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-danger-custom']) }}>
    {{ $slot }}
</button>