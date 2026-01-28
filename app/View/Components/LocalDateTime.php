<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Carbon\Carbon;

class LocalDateTime extends Component
{
    public $datetime;
    public $format;
    
    public function __construct($datetime, $format = 'full')
    {
        $this->datetime = $datetime instanceof Carbon ? $datetime : Carbon::parse($datetime);
        $this->format = $format;
    }
    
    public function render()
    {
        return view('components.local-date-time');
    }
}