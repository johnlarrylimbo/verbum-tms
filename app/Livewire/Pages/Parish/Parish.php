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
  public $edit_address;
  public $edit_contact_number;
  public $edit_parish_priest_id;
  public $edit_established_year;

  #modals
  public bool $addParishModal = false;
  public bool $editParishModal = false;
  public bool $updateParishStatusModal = false;
  public bool $addPriestModal = false;

  public $showAddSuccessMessage = false;
  public $showAddErrorMessage = false;
  public $showSuccessMessage = false;
  public $showErrorMessage = false;

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
    if(!$this->search){
      $parish_lst = $this->parish_service->loadParishLst()->paginate(15);
		  return $parish_lst;
    }else{
      $parish_lst = $this->parish_service->loadParishLstByKeyword($this->search)->paginate(15);
		  return $parish_lst;
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
  public function save(){
		// Validation and saving logic

		$this->validate([
      'diocese_id' => 'required|not_in:0',
      'vicariate_id' => 'required|not_in:0',
      'name' => 'required|string|max:2048',
      'address' => 'required|string|max:2048',
      'contact_number' => 'required|string|max:64',
      'parish_priest_id' => 'required|not_in:0',
      'established_year' => 'required|string|max:64'
		]);

    $exists = $this->parish_service->addParish($this->diocese_id, $this->vicariate_id, $this->name, $this->address, $this->contact_number, $this->parish_priest_id, $this->established_year, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
      $this->showAddErrorMessage = true;
		}
		else{
      $this->showAddSuccessMessage = true;
		}

		// Optionally reset form fields after save
    $this->reset(['diocese_id', 'diocese_id']);
		$this->reset(['vicariate_id', 'vicariate_id']);
    $this->reset(['name', 'name']);
    $this->reset(['address', 'address']);
    $this->reset(['contact_number', 'contact_number']);
    $this->reset(['parish_priest_id', 'parish_priest_id']);
    $this->reset(['established_year', 'established_year']);

		// Close the modal
		$this->addParishModal = false;

		$this->parish_lst();
	}

  // public function get parish by id
	public function openEditParishModal(int $parish_id){
    $this->resetValidation();  // clears validation errors
		$this->editParishModal = true;
		$this->parish_id = $parish_id;

    $result = $this->parish_service->getParishById($this->parish_id);

		foreach($result as $result){
      $this->edit_diocese_id = $result->diocese_id;
      $this->edit_vicariate_id = $result->vicariate_id;
      $this->edit_name = $result->name;
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
		// Validation and saving logic
    $this->validate([
      'edit_diocese_id' => 'required|not_in:0',
      'edit_vicariate_id' => 'required|not_in:0',
      'edit_name' => 'required|string|max:2048',
      'edit_address' => 'required|string|max:2048',
      'edit_contact_number' => 'required|string|max:64',
      'edit_parish_priest_id' => 'required|not_in:0',
      'edit_established_year' => 'required|string|max:64'
		]);

    $exists = $this->parish_service->updateParishById($this->parish_id, $this->edit_diocese_id, $this->edit_vicariate_id, $this->edit_name, $this->edit_address, $this->edit_contact_number, $this->edit_parish_priest_id, $this->edit_established_year, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
      $this->showErrorMessage = true;
		}
		else{
      $this->showSuccessMessage = true;
		}

		// Optionally reset form fields after save
    $this->reset(['parish_id', 'parish_id']);
    $this->reset(['edit_diocese_id', 'diocese_id']);
		$this->reset(['edit_vicariate_id', 'vicariate_id']);
    $this->reset(['edit_name', 'name']);
    $this->reset(['edit_address', 'address']);
    $this->reset(['edit_contact_number', 'contact_number']);
    $this->reset(['edit_parish_priest_id', 'parish_priest_id']);
    $this->reset(['edit_established_year', 'established_year']);

		// Close the modal
		$this->editParishModal = false;

		$this->parish_lst();
	}

  // public function save barangay record changes
  public function save_new_priest(){
		// Validation and saving logic

		$this->validate([
      'firstname' => 'required|string|max:64',
      'lastname' => 'required|string|max:64',
      'congregation_id' => 'required|not_in:0'
		]);

    $exists = $this->priest_service->addPriest($this->firstname, $this->middlename, $this->lastname, $this->congregation_id, auth()->user()->id);

		if ($exists[0]->result_id == 1) {
			// $this->error('Failed to update record. Record does not exists.');
      $this->showAddErrorMessage = true;
		}
		else{
      // $this->success('Record updated successfully!');
      $this->showAddSuccessMessage = true;
		}

		// Optionally reset form fields after save
		$this->reset(['firstname', 'firstname']);
    $this->reset(['middlename', 'middlename']);
    $this->reset(['lastname', 'lastname']);
    $this->reset(['congregation_id', 'congregation_id']);

		// Close the modal
		$this->addPriestModal = false;
	}

  public function openUpdateParishStatusModal(int $parish_id, int $statuscode){
		$this->updateParishStatusModal = true;
		$this->parish_id = $parish_id;
    $this->statuscode = $statuscode;
	}

  public function update_parish_status($parish_id, $statuscode){

    $result = $this->parish_service->updateParishStatusById($parish_id, $statuscode, auth()->user()->id);
		
		// // Toast
    if ($result[0]->result_id > 0) {
      $this->showSuccessMessage = true;
    }else{
      $this->showErrorMessage = true;
    }

		$this->updateParishStatusModal = false;	
	}


	public function render(){
		return view('livewire.pages.parish.parish');
	}

}
