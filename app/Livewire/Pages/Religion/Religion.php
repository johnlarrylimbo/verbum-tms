<?php

namespace App\Livewire\Pages\Religion;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\ReligionService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class Religion extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $religion_service;

  #public variables
  public $search;
  public $religion_id;
  public $statuscode;

  public $label;

  public $edit_label;

  #modals
  public bool $addReligionModal = false;
  public bool $editReligionModal = false;
  public bool $updateReligionStatusModal = false;

  public $showAddSuccessMessage = false;
  public $showAddErrorMessage = false;
  public $showSuccessMessage = false;
  public $showErrorMessage = false;

	public function boot(
		ReligionService $religion_service,
	)
	{
		$this->religion_service = $religion_service;
	}

  public function mount(){
		// Initialize form fields
    $this->label = '';
	}

  public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load religion records
	public function religion_lst(){
    if(!$this->search){
      $religion_lst = $this->religion_service->loadReligionLst()->paginate(15);
		  return $religion_lst;
    }else{
      $religion_lst = $this->religion_service->loadReligionLstByKeyword($this->search)->paginate(15);
		  return $religion_lst;
    }
	}

  // public function save religion record changes
  public function save_religion(){
		// Validation and saving logic

		$this->validate([
      'label' => 'required|string|max:255'
		]);

    $exists = $this->religion_service->addReligion($this->label, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
      $this->showAddErrorMessage = true;
		}
		else{
      $this->showAddSuccessMessage = true;
		}

		// Optionally reset form fields after save
    $this->reset(['label', 'label']);

		// Close the modal
		$this->addReligionModal = false;

		$this->religion_lst();
	}

  // public function get religion by id
	public function openEditReligionModal(int $religion_id){
    $this->resetValidation();  // clears validation errors
		$this->editReligionModal = true;
		$this->religion_id = $religion_id;

    $result = $this->religion_service->getReligionById($this->religion_id);

		foreach($result as $result){
      $this->edit_label = $result->label;
		}
	}

  // public function save employee type record changes
  public function save_religion_record_changes(){
		// Validation and saving logic
    $this->validate([
      'edit_label' => 'required|string|max:255'
		]);

    $exists = $this->religion_service->updateReligionById($this->religion_id, $this->edit_label, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
      $this->showErrorMessage = true;
		}
		else{
      $this->showSuccessMessage = true;
		}

		// Optionally reset form fields after save
    $this->reset(['religion_id', 'religion_id']);
    $this->reset(['edit_label', 'edit_label']);

		// Close the modal
		$this->editReligionModal = false;

		$this->religion_lst();
	}

  public function openUpdateReligionStatusModal(int $religion_id, int $statuscode){
		$this->updateReligionStatusModal = true;
		$this->religion_id = $religion_id;
    $this->statuscode = $statuscode;
	}

  public function update_religion_status($religion_id, $statuscode){

    $result = $this->religion_service->updateReligionStatusById($religion_id, $statuscode, auth()->user()->id);
		
		// // Toast
    if ($result[0]->result_id > 0) {
      $this->showSuccessMessage = true;
    }else{
      $this->showErrorMessage = true;
    }

		$this->updateReligionStatusModal = false;	
	}


	public function render(){
		return view('livewire.pages.religion.religion');
	}

}
