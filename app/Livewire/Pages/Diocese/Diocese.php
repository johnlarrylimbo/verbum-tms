<?php

namespace App\Livewire\Pages\Diocese;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\DioceseService;
use App\Services\SelectOptionLibraryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class Diocese extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $diocese_service;
  protected $select_option_service;

  #public variables
  public $search;
  public $diocese_id;
  public $statuscode;

  public $name;
  public $archbishop_id;
  public $vicar_general_id;
  public $chancellor_id;
  public $address;
  public $contact_number;
  public $email_address;

  public $edit_name;
  public $edit_archbishop_id;
  public $edit_vicar_general_id;
  public $edit_chancellor_id;
  public $edit_address;
  public $edit_contact_number;
  public $edit_email_address;

  #modals
  public bool $addDioceseModal = false;
  public bool $editDioceseModal = false;
  public bool $updateDioceseStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		DioceseService $diocese_service,
    SelectOptionLibraryService $select_option_service,
	)
	{
		$this->diocese_service = $diocese_service;
    $this->select_option_service = $select_option_service;
	}

  public function mount(){
		// Initialize form fields
		$this->name = '';
    $this->archbishop_id = 0;
    $this->vicar_general_id = 0;
    $this->chancellor_id = 0;
    $this->address = '';
    $this->contact_number = '';
    $this->email_address = '';
	}

  public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function loadRecords
	public function diocese_lst(){
    try{
      if(!$this->search){
        $diocese_lst = $this->diocese_service->loadDioceseLst()->paginate(15);
        return $diocese_lst;
      }else{
        $diocese_lst = $this->diocese_service->loadDioceseLstByKeyword($this->search)->paginate(15);
        return $diocese_lst;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  #[Computed]
	// public function load priest options
	public function load_priest_options(){
		return $this->select_option_service->loadPriestOptions();
	}

  // public function save diocese record changes
  public function save_diocese(){
    try{
      // Validation and saving logic
      $this->validate([
        'name' => 'required|string|max:2048',
        'archbishop_id' => 'required|not_in:0',
        'vicar_general_id' => 'required|not_in:0',
        'chancellor_id' => 'required|not_in:0',
        'address' => 'required|string|max:2048',
        'contact_number' => 'required|string|max:256',
        'email_address' => 'required|string|max:256'
      ]);

      $exists = $this->diocese_service->addDiocese($this->name, $this->archbishop_id, $this->vicar_general_id, $this->chancellor_id, $this->address, $this->contact_number, $this->email_address, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new diocese successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['name', 'name']);
      $this->reset(['archbishop_id', 'archbishop_id']);
      $this->reset(['vicar_general_id', 'vicar_general_id']);
      $this->reset(['chancellor_id', 'chancellor_id']);
      $this->reset(['address', 'address']);
      $this->reset(['contact_number', 'contact_number']);
      $this->reset(['email_address', 'email_address']);

      // Close the modal
      $this->addDioceseModal = false;

      $this->diocese_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get diocese by id
	public function openEditDioceseModal(int $diocese_id){
    try{
      $this->resetValidation();  // clears validation errors
      $this->editDioceseModal = true;
      $this->diocese_id = $diocese_id;

      $result = $this->diocese_service->getDioceseById($this->diocese_id);

      foreach($result as $result){
        $this->edit_name = $result->diocese_name;
        $this->edit_archbishop_id = $result->archbishop_id;
        $this->edit_vicar_general_id = $result->vicar_general_id;
        $this->edit_chancellor_id = $result->chancellor_id;
        $this->edit_address = $result->address;
        $this->edit_contact_number = $result->contact_number;
        $this->edit_email_address = $result->email_address;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save diocese record changes
  public function save_diocese_record_changes(){
    try{
      // Validation and saving logic
      $this->validate([
        'edit_name' => 'required|string|max:2048',
        'edit_archbishop_id' => 'required|not_in:0',
        'edit_vicar_general_id' => 'required|not_in:0',
        'edit_chancellor_id' => 'required|not_in:0',
        'edit_address' => 'required|string|max:2048',
        'edit_contact_number' => 'required|string|max:256',
        'edit_email_address' => 'required|string|max:256'
      ]);

      $exists = $this->diocese_service->updateDioceseById($this->diocese_id, $this->edit_name, $this->edit_archbishop_id, $this->edit_vicar_general_id, $this->edit_chancellor_id, $this->edit_address, $this->edit_contact_number, $this->edit_email_address, auth()->user()->id);

      if ($exists[0]->result_id == 0) {
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Updated diocese successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['diocese_id', 'diocese_id']);
      $this->reset(['edit_name', 'edit_name']);
      $this->reset(['edit_archbishop_id', 'edit_archbishop_id']);
      $this->reset(['edit_vicar_general_id', 'edit_vicar_general_id']);
      $this->reset(['edit_chancellor_id', 'edit_chancellor_id']);
      $this->reset(['edit_address', 'edit_address']);
      $this->reset(['edit_contact_number', 'edit_contact_number']);
      $this->reset(['edit_email_address', 'edit_email_address']);

      // Close the modal
      $this->editDioceseModal = false;

      $this->diocese_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateDioceseStatusModal(int $diocese_id, int $statuscode){
    try{
      $this->updateDioceseStatusModal = true;
      $this->diocese_id = $diocese_id;
      $this->statuscode = $statuscode;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_diocese_status($diocese_id, $statuscode){
    try{
      $result = $this->diocese_service->updateDioceseStatusById($diocese_id, $statuscode, auth()->user()->id);
      
      // // Toast
      if ($result[0]->result_id > 0) {
        // Optional: Show error to user
        $this->addMessage = 'Updated diocese status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }else{
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }

      $this->updateDioceseStatusModal = false;	
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}


	public function render(){
		return view('livewire.pages.diocese.diocese');
	}

}
