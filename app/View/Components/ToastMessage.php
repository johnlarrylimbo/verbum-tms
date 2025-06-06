<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ToastMessage extends Component
{
    public bool $isSuccess;
    public string $message;

    public function __construct(bool $isSuccess = false, string $message = '')
    {
        $this->isSuccess = $isSuccess;
        $this->message = $message;
    }


    public function render()
    {
        return view('components.toast-message');
    }
}
