<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LivewireLoader extends Component
{
    public string $target;
    public string $message;

    public function __construct(string $target, string $message)
    {
        $this->target = $target;
        $this->message = $message;
    }

    public function render()
    {
        return view('components.livewire-loader');
    }
}
