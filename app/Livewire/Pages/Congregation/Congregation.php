<?php

namespace App\Livewire\Pages\Congregation;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\CongregationService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class Congregation extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $congregation_service;

  #public variables
  public $search;
  public $congregation_id;
  public $statuscode;

  public $abbreviation;
  public $description;

  public $edit_abbreviation;
  public $edit_description;

  #modals
  public bool $addCongregationModal = false;
  public bool $editCongregationModal = false;
  public bool $updateCongregationStatusModal = false;

  public $showAddSuccessMessage = false;
  public $showAddErrorMessage = false;
  public $showSuccessMessage = false;
  public $showErrorMessage = false;

	public function boot(
		CongregationService $congregation_service,
	)
	{
		$this->congregation_service = $congregation_service;
	}

  public function mount(){
		// Initialize form fields
		$this->abbreviation = '';
    $this->description = '';
	}

  public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function loadRecords
	public function congregation_lst(){
    if(!$this->search){
      $congregation_lst = $this->congregation_service->loadCongregationLst()->paginate(15);
		  return $congregation_lst;
    }else{
      $congregation_lst = $this->congregation_service->loadCongregationLstByKeyword($this->search)->paginate(15);
		  return $congregation_lst;
    }
	}

  // public function save congregation record changes
  public function save(){
		// Validation and saving logic

		$this->validate([
      'abbreviation' => 'required|string|max:45',
      'description' => 'required|string|max:2048'
		]);

    $exists = $this->congregation_service->addCongregation($this->abbreviation, $this->description, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
			// $this->error('Failed to update record. Record does not exists.');
      $this->showAddErrorMessage = true;
		}
		else{
      // $this->success('Record updated successfully!');
      $this->showAddSuccessMessage = true;
		}

		// Optionally reset form fields after save
		$this->reset(['abbreviation', 'abbreviation']);
    $this->reset(['description', 'description']);

		// Close the modal
		$this->addCongregationModal = false;

		$this->congregation_lst();
	}

  // public function get congregation by id
	public function openEditCongregationModal(int $congregation_id){
    $this->resetValidation();  // clears validation errors
		$this->editCongregationModal = true;
		$this->congregation_id = $congregation_id;

    $result = $this->congregation_service->getCongregationById($this->congregation_id);

		foreach($result as $result){
      $this->edit_abbreviation = $result->abbreviation;
      $this->edit_description = $result->congregation_label;
		}
	}

  // public function save congregation record changes
  public function save_congregation_record_changes(){
		// Validation and saving logic

		$this->validate([
      'edit_abbreviation' => 'required|string|max:45',
      'edit_description' => 'required|string|max:2048'
		]);

    $exists = $this->congregation_service->updateCongregationById($this->congregation_id, $this->edit_abbreviation, $this->edit_description, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
      $this->showErrorMessage = true;
		}
		else{
      $this->showSuccessMessage = true;
		}

		// Optionally reset form fields after save
		$this->reset(['congregation_id', 'congregation_id']);
    $this->reset(['edit_abbreviation', 'edit_abbreviation']);
    $this->reset(['edit_description', 'edit_description']);

		// Close the modal
		$this->editCongregationModal = false;

		$this->congregation_lst();
	}

  public function openUpdateCongregationStatusModal(int $congregation_id, int $statuscode){
		$this->updateCongregationStatusModal = true;
		$this->congregation_id = $congregation_id;
    $this->statuscode = $statuscode;
	}

  public function update_congregation_status($congregation_id, $statuscode){

    $result = $this->congregation_service->updateCongregationStatusById($congregation_id, $statuscode, auth()->user()->id);
		
		// // Toast
    if ($result[0]->result_id > 0) {
      $this->showSuccessMessage = true;
    }else{
      $this->showErrorMessage = true;
    }

		$this->updateCongregationStatusModal = false;	
	}


	public function render(){
		return view('livewire.pages.congregation.congregation');
	}

}
