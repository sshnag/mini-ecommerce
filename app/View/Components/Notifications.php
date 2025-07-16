<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Notifications extends Component
{
    public $notifications;

    public function __construct()
    {
    }

    public function render()
    {
        return view('components.notifications');
    }
}
