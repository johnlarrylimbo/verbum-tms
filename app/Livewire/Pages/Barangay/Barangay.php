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

  #protected variables
	protected $barangay_service;

  #public variables
  public $search;

  #modals

	public function boot(
		BarangayService $barangay_service,
	)
	{
		$this->barangay_service = $barangay_service;
	}

  #[Computed]
	// public function loadRecords
	public function barangay_lst(){
    if(!$this->search){
      $barangay_lst = $this->barangay_service->loadBarangayLst()->paginate(15);
		  return $barangay_lst;
    }else{
      $barangay_lst = $this->barangay_service->loadBarangayLstByKeyword($this->search)->paginate(15);
		  return $barangay_lst;
    }
	}


	public function mount(){
		// Initialize form fields
    // $this->clearance_area_id = 0;
	}


	public function render(){
		return view('livewire.pages.barangay.barangay');
	}

}
