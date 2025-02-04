<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\HealthClaimCategory;

class HealthClaimCategoryForm extends Form
{
	#[Validate('string', 'required')]
  public $description;

	// public function store(){
	// 	$this->Validate();

	// 	HealthClaimCategory::create([
	// 		'label' => $this->description
	// 	]);

	// 	$this->reset();

	// }
}
