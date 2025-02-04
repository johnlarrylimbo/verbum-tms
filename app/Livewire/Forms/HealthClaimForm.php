<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class HealthClaimForm extends Form
{

    #[Validate('required|string|max:128')]
    public string $label;

    #[Validate('required')]
    public int $health_claim_category_id;
}
