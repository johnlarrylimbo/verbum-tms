<?php

namespace App\Livewire\Pages\Barangay;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\BarangayService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class Barangay extends Component
{

	use WithPagination;
	use Toast;

	protected $barangay_service;

	public bool $addAccessPermissionRequestModal = false;
	public bool $editAccessPermissionRequestModal = false;
	public bool $deleteAccessPermissionRequestModal = false;

	public $access_permission_request_id;

	public $clearance_area_id;

  public $edit_clearance_area_id;	

	public function boot(
		BarangayService $barangay_service,
	)
	{
		$this->barangay_service = $barangay_service;
	}


	public function mount(){
		// Initialize form fields
    $this->clearance_area_id = 0;
	}


	public function render(){
		return view('livewire.pages.barangay.barangay');
	}

}
