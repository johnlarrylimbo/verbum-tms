<?php

namespace App\Livewire\Pages\Parish;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\ParishService;
use App\Services\PriestService;
use App\Services\SelectOptionLibraryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class Parish extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $parish_service;
  protected $select_option_service;

  #public variables
  public $search;
  public $parish_id;
  public $statuscode;

  public $diocese_id;
  public $vicariate_id;
  public $name;
  public $primary_location;
  public $address;
  public $contact_number;
  public $parish_priest_id;
  public $established_year;

  public $lastname;
  public $firstname;
  public $middlename;
  public $congregation_id;

  public $edit_diocese_id;
  public $edit_vicariate_id;
  public $edit_name;
  public $edit_primary_location;
  public $edit_address;
  public $edit_contact_number;
  public $edit_parish_priest_id;
  public $edit_established_year;

  #modals
  public bool $addParishModal = false;
  public bool $editParishModal = false;
  public bool $updateParishStatusModal = false;
  public bool $addPriestModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

  public $vicariate_options = [];

	public function boot(
		ParishService $parish_service,
    PriestService $priest_service,
    SelectOptionLibraryService $select_option_service,
	)
	{
		$this->parish_service = $parish_service;
    $this->priest_service = $priest_service;
    $this->select_option_service = $select_option_service;
	}

  public function mount(){
		// Initialize form fields
    $this->diocese_id = 0;
    $this->vicariate_id = 0;
    $this->name = '';
    $this->primary_location = '';
    $this->address = '';
    $this->contact_number = '';
    $this->parish_priest_id = 0;
    $this->established_year = '';

    $this->lastname = '';
    $this->firstname = '';
    $this->middlename = '';
    $this->congregation_id = 0;
	}

  public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load parish records
	public function parish_lst(){
    try{
      if(!$this->search){
        $parish_lst = $this->parish_service->loadParishLst()->paginate(15);
        return $parish_lst;
      }else{
        $parish_lst = $this->parish_service->loadParishLstByKeyword($this->search)->paginate(15);
        return $parish_lst;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  #[Computed]
	// public function load diocese options
	public function load_diocese_options(){
		return $this->select_option_service->loadDioceseOptions();
	}

  //public function load vicariate when diocese change
  public function dioceseChanged()
  {
    $diocese_id = $this->diocese_id;

    if (!$diocese_id) {
        $this->vicariate_options = [];
        return;
    }

    // Update the property that holds vicariate options
    $this->vicariate_options = $this->select_option_service->loadVicariateByDioceseIdOptions($diocese_id);
  }

  #[Computed]
	// public function load priest options
	public function load_priest_options(){
		return $this->select_option_service->loadPriestOptions();
	}

  #[Computed]
	// public function load congregation options
	public function load_congregation_options(){
		return $this->select_option_service->loadCongregationOptions();
	}

  // public function save diocese record changes
  public function save_parish(){
    try{
      // Validation and saving logic
      $this->validate([
        'diocese_id' => 'required|not_in:0',
        'vicariate_id' => 'required|not_in:0',
        'name' => 'required|string|max:2048',
        'primary_location' => 'required|string|max:256',
        'address' => 'required|string|max:2048',
        'contact_number' => 'required|string|max:64',
        'parish_priest_id' => 'required|not_in:0',
        'established_year' => 'required|string|max:64'
      ]);

      $exists = $this->parish_service->addParish($this->diocese_id, $this->vicariate_id, $this->name, $this->primary_location, $this->address, $this->contact_number, $this->parish_priest_id, $this->established_year, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new parish successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['diocese_id', 'diocese_id']);
      $this->reset(['vicariate_id', 'vicariate_id']);
      $this->reset(['name', 'name']);
      $this->reset(['primary_location', 'primary_location']);
      $this->reset(['address', 'address']);
      $this->reset(['contact_number', 'contact_number']);
      $this->reset(['parish_priest_id', 'parish_priest_id']);
      $this->reset(['established_year', 'established_year']);

      // Close the modal
      $this->addParishModal = false;

      $this->parish_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get parish by id
	public function openEditParishModal(int $parish_id){
    try{
      $this->resetValidation();  // clears validation errors
      $this->editParishModal = true;
      $this->parish_id = $parish_id;

      $result = $this->parish_service->getParishById($this->parish_id);

      foreach($result as $result){
        $this->edit_diocese_id = $result->diocese_id;
        $this->edit_vicariate_id = $result->vicariate_id;
        $this->edit_name = $result->name;
        $this->edit_primary_location = $result->primary_location;
        $this->edit_address = $result->address;
        $this->edit_contact_number = $result->contact_number;
        $this->edit_parish_priest_id = $result->parish_priest_id;
        $this->edit_established_year = $result->established_year;
      }

      $diocese_id = $this->edit_diocese_id;

      if (!$diocese_id) {
          $this->vicariate_options = [];
          return;
      }
      // Update the property that holds vicariate options
      $this->vicariate_options = $this->select_option_service->loadVicariateByDioceseIdOptions($diocese_id);
      
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  //public function load vicariate when diocese change
  public function edit_dioceseChanged()
  {
    $diocese_id = $this->edit_diocese_id;

    if (!$diocese_id) {
        $this->vicariate_options = [];
        return;
    }

    // Update the property that holds vicariate options
    $this->vicariate_options = $this->select_option_service->loadVicariateByDioceseIdOptions($diocese_id);
  }

  // public function save parish record changes
  public function save_parish_record_changes(){
    try{
      // Validation and saving logic
      $this->validate([
        'edit_diocese_id' => 'required|not_in:0',
        'edit_vicariate_id' => 'required|not_in:0',
        'edit_name' => 'required|string|max:2048',
        'edit_primary_location' => 'required|string|max:256',
        'edit_address' => 'required|string|max:2048',
        'edit_contact_number' => 'required|string|max:64',
        'edit_parish_priest_id' => 'required|not_in:0',
        'edit_established_year' => 'required|string|max:64'
      ]);

      $exists = $this->parish_service->updateParishById($this->parish_id, $this->edit_diocese_id, $this->edit_vicariate_id, $this->edit_name, $this->edit_primary_location, $this->edit_address, $this->edit_contact_number, $this->edit_parish_priest_id, $this->edit_established_year, auth()->user()->id);

      if ($exists[0]->result_id == 0) {
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Updated parish successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['parish_id', 'parish_id']);
      $this->reset(['edit_diocese_id', 'edit_diocese_id']);
      $this->reset(['edit_vicariate_id', 'edit_vicariate_id']);
      $this->reset(['edit_name', 'edit_name']);
      $this->reset(['edit_primary_location', 'edit_primary_location']);
      $this->reset(['edit_address', 'edit_address']);
      $this->reset(['edit_contact_number', 'edit_contact_number']);
      $this->reset(['edit_parish_priest_id', 'edit_parish_priest_id']);
      $this->reset(['edit_established_year', 'edit_established_year']);

      // Close the modal
      $this->editParishModal = false;

      $this->parish_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save barangay record changes
  public function save_new_priest(){
    try{
      // Validation and saving logic
      $this->validate([
        'firstname' => 'required|string|max:64',
        'lastname' => 'required|string|max:64',
        'congregation_id' => 'required|not_in:0'
      ]);

      $exists = $this->priest_service->addPriest($this->firstname, $this->middlename, $this->lastname, $this->congregation_id, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new priest successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['firstname', 'firstname']);
      $this->reset(['middlename', 'middlename']);
      $this->reset(['lastname', 'lastname']);
      $this->reset(['congregation_id', 'congregation_id']);

      // Close the modal
      $this->addPriestModal = false;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateParishStatusModal(int $parish_id, int $statuscode){
    try{
      $this->updateParishStatusModal = true;
      $this->parish_id = $parish_id;
      $this->statuscode = $statuscode;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_parish_status($parish_id, $statuscode){
    try{
      $result = $this->parish_service->updateParishStatusById($parish_id, $statuscode, auth()->user()->id);
      
      // // Toast
      if ($result[0]->result_id > 0) {
        // Optional: Show error to user
        $this->addMessage = 'Updated parish status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }else{
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }

      $this->updateParishStatusModal = false;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }	
	}


	public function render(){
		return view('livewire.pages.parish.parish');
	}

}
