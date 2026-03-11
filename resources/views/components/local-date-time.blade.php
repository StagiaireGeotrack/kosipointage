<span class="local-datetime" 
      data-utc="{{ $datetime->utc()->toIso8601ZuluString() }}"
      data-format="{{ $format ?? 'full' }}">
    {{ ucfirst($datetime->isoFormat('dddd D MMMM YYYY - HH:mm:ss')) }}
</span>