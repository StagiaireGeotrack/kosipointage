<span class="local-datetime" 
      data-utc="{{ $datetime->toIso8601String() }}"
      data-format="{{ $format }}">
    {{ ucfirst($datetime->isoFormat('dddd D MMMM YYYY - HH:mm:ss')) }}
</span>