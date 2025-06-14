<?php

namespace App\Livewire\Pages\Designation;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\DesignationService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class Designation extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $designation_service;

  #public variables
  public $search;
  public $designation_id;
  public $statuscode;

  public $abbreviation, $label;

  public $edit_abbreviation, $edit_label;

  #modals
  public bool $addDesignationModal = false;
  public bool $editDesignationModal = false;
  public bool $updateDesignationStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		DesignationService $designation_service,
	)
	{
		$this->designation_service = $designation_service;
	}

  public function mount(){
		// Initialize form fields
    $this->abbreviation = '';
    $this->label = '';
	}

  public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load designation records
	public function designation_lst(){
		try{
			if(!$this->search){
				$designation_lst = $this->designation_service->loadDesignationLst()->paginate(15);
				return $designation_lst;
			}else{
				$designation_lst = $this->designation_service->loadDesignationLstByKeyword($this->search)->paginate(15);
				return $designation_lst;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save designation record changes
  public function save_designation(){
		try{
			// Validation and saving logic
			$this->validate([
        'abbreviation' => 'required|string|max:64',
				'label' => 'required|string|max:256'
			]);

			$exists = $this->designation_service->addDesignation($this->abbreviation, $this->label, auth()->user()->id);

			if ($exists[0]->result_id == 1) {
				// Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Added new designation successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['abbreviation', 'abbreviation']);
      $this->reset(['abbrevialabeltion', 'label']);

			// Close the modal
			$this->addDesignationModal = false;

			$this->designation_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get designation by id
	public function openEditDesignationModal(int $designation_id){
		try{
			$this->resetValidation();  // clears validation errors
			$this->editDesignationModal = true;
			$this->designation_id = $designation_id;

			$result = $this->designation_service->getDesignationById($this->designation_id);

			foreach($result as $result){
        $this->edit_abbreviation = $result->abbreviation;
				$this->edit_label = $result->label;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save designation record changes
  public function save_designation_record_changes(){
		try{
			// Validation and saving logic
			$this->validate([
        'edit_abbreviation' => 'required|string|max:64',
				'edit_label' => 'required|string|max:256'
			]);

			$exists = $this->designation_service->updateDesignationById($this->designation_id, $this->edit_abbreviation, $this->edit_label, auth()->user()->id);

			if ($exists[0]->result_id == 0) {
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Updated designation successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['designation_id', 'designation_id']);
      $this->reset(['edit_abbreviation', 'edit_abbreviation']);
			$this->reset(['edit_label', 'edit_label']);

			// Close the modal
			$this->editDesignationModal = false;

			$this->designation_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateDesignationStatusModal(int $designation_id, int $statuscode){
		try{
			$this->updateDesignationStatusModal = true;
			$this->designation_id = $designation_id;
			$this->statuscode = $statuscode;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_designation_status($designation_id, $statuscode){
		try{
			$result = $this->designation_service->updateDesignationStatusById($designation_id, $statuscode, auth()->user()->id);
			
			// // Toast
			if ($result[0]->result_id > 0) {
				// Optional: Show error to user
        $this->addMessage = 'Updated designation status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}else{
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}

			$this->updateDesignationStatusModal = false;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }	
	}


	public function render(){
		return view('livewire.pages.designation.designation');
	}

}
