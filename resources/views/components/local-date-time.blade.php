<span class="local-datetime" 
      data-utc="{{ $datetime->utc()->toIso8601String() }}"
      data-format="{{ $format ?? 'dddd D MMMM YYYY - HH:mm:ss' }}">
    {{ ucfirst($datetime->isoFormat('dddd D MMMM YYYY - HH:mm:ss')) }}
</span>