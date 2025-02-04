<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Livewire\Actions\Logout;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    //
    public function logout(Logout $logout): RedirectResponse
    {
        $logout();

        // $this->redirect('/', navigate: true);

        return redirect()->intended('/login');
    }
}
