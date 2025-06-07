<?php

namespace App\Livewire\Pages\Citizenship;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\CitizenshipService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class Citizenship extends Component
{

	use WithPagination;
	use Toast;

	#protected variables
	protected $citizenship_service;

  #public variables
  public $search;
  public $citizenship_id;
  public $statuscode;

  public $abbreviation;
  public $label;
  public $nationality;

  public $edit_abbreviation;
  public $edit_label;
  public $edit_nationality;

  #modals
  public bool $addCitizenshipModal = false;
  public bool $editCitizenshipModal = false;
  public bool $updateCitizenshipStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		CitizenshipService $citizenship_service,
	)
	{
		$this->citizenship_service = $citizenship_service;
	}

	public function mount(){
		// Initialize form fields
    $this->abbreviation = '';
    $this->label = '';
    $this->nationality = '';
	}

	public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load citizenship records
	public function citizenship_lst(){
    try{
      if(!$this->search){
        $citizenship_lst = $this->citizenship_service->loadCitizenshipLst()->paginate(15);
        return $citizenship_lst;
      }else{
        $citizenship_lst = $this->citizenship_service->loadCitizenshipLstByKeyword($this->search)->paginate(15);
        return $citizenship_lst;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save citizenship record changes
  public function save_citizenship(){
    try{
      // Validation and saving logic
      $this->validate([
        'abbreviation' => 'required|string|max:15',
        'label' => 'required|string|max:255',
        'nationality' => 'required|string|max:255'
      ]);

      $exists = $this->citizenship_service->addCitizenship($this->abbreviation, $this->label, $this->nationality, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new citizenship successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['abbreviation', 'abbreviation']);
      $this->reset(['label', 'label']);
      $this->reset(['nationality', 'nationality']);

      // Close the modal
      $this->addCitizenshipModal = false;

      $this->citizenship_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get citizenship by id
	public function openEditCitizenshipModal(int $citizenship_id){
    try{
      $this->resetValidation();  // clears validation errors
      $this->editCitizenshipModal = true;
      $this->citizenship_id = $citizenship_id;

      $result = $this->citizenship_service->getCitizenshipById($this->citizenship_id);

      foreach($result as $result){
        $this->edit_abbreviation = $result->abbreviation;
        $this->edit_label = $result->label;
        $this->edit_nationality = $result->nationality;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save citizenship record changes
  public function save_citizenship_record_changes(){
    try{
      // Validation and saving logic
      $this->validate([
        'edit_abbreviation' => 'required|string|max:15',
        'edit_label' => 'required|string|max:255',
        'edit_nationality' => 'required|string|max:255'
      ]);

      $exists = $this->citizenship_service->updateCitizenshipById($this->citizenship_id, $this->edit_abbreviation, $this->edit_label, $this->edit_nationality, auth()->user()->id);

      if ($exists[0]->result_id == 0) {
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Updated citizenship successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['citizenship_id', 'citizenship_id']);
      $this->reset(['edit_abbreviation', 'edit_abbreviation']);
      $this->reset(['edit_label', 'edit_label']);
      $this->reset(['edit_nationality', 'edit_nationality']);

      // Close the modal
      $this->editCitizenshipModal = false;

      $this->citizenship_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateCitizenshipStatusModal(int $citizenship_id, int $statuscode){
    try{
      $this->updateCitizenshipStatusModal = true;
      $this->citizenship_id = $citizenship_id;
      $this->statuscode = $statuscode;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_citizenship_status($citizenship_id, $statuscode){
    try{
      $result = $this->citizenship_service->updateCitizenshipStatusById($citizenship_id, $statuscode, auth()->user()->id);
      
      // // Toast
      if ($result[0]->result_id > 0) {
        // Optional: Show error to user
        $this->addMessage = 'Updated citizenship status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }else{
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }

      $this->updateCitizenshipStatusModal = false;	
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}


	public function render(){
		return view('livewire.pages.citizenship.citizenship');
	}

}
