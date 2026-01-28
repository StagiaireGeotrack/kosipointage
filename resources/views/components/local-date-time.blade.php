<span class="auto-timezone" 
      data-date="{{ $datetime->toIso8601String() }}"
      data-format="{{ $format }}">
    {{ ucfirst($datetime->isoFormat('dddd D MMMM YYYY - HH:mm:ss')) }}
</span>