<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-secondary text-uppercase fw-semibold']) }}>
    {{ $slot }}
</button>