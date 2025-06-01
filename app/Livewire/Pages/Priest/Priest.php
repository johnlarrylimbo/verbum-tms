<?php

namespace App\Livewire\Pages\Priest;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\PriestService;
use App\Services\SelectOptionLibraryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class Priest extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $priest_service;
  protected $select_option_service;

  #public variables
  public $search;
  public $priest_id;
  public $statuscode;

  public $lastname;
  public $firstname;
  public $middlename;
  public $congregation_id;

  public $edit_lastname;
  public $edit_firstname;
  public $edit_middlename;
  public $edit_congregation_id;

  #modals
  public bool $addPriestModal = false;
  public bool $editPriestModal = false;
  public bool $updatePriestStatusModal = false;
  public $showAddSuccessMessage = false;
  public $showAddErrorMessage = false;
  public $showSuccessMessage = false;
  public $showErrorMessage = false;

	public function boot(
		PriestService $priest_service,
    SelectOptionLibraryService $select_option_service,
	)
	{
		$this->priest_service = $priest_service;
    $this->select_option_service = $select_option_service;
	}

  public function mount(){
		// Initialize form fields
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
	// public function load priest records
	public function priest_lst(){
    if(!$this->search){
      $priest_lst = $this->priest_service->loadPriestLst()->paginate(15);
		  return $priest_lst;
    }else{
      $priest_lst = $this->priest_service->loadPriestLstByKeyword($this->search)->paginate(15);
		  return $priest_lst;
    }
	}

  #[Computed]
	// public function load congregation options
	public function load_congregation_options(){
		return $this->select_option_service->loadCongregationOptions();
	}

  // public function save barangay record changes
  public function save(){
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

		$this->priest_lst();
	}

  // public function get priest by id
	public function openEditPriestModal(int $priest_id){
    $this->resetValidation();  // clears validation errors
		$this->editPriestModal = true;
		$this->priest_id = $priest_id;

    $result = $this->priest_service->getPriestById($this->priest_id);

		foreach($result as $result){
			$this->edit_firstname = $result->firstname;
      $this->edit_middlename = $result->middlename;
      $this->edit_lastname = $result->lastname;
      $this->edit_congregation_id = $result->congregation_id;
		}
	}

  // public function save priest record changes
  public function save_priest_record_changes(){
		// Validation and saving logic

		$this->validate([
      'edit_firstname' => 'required|string|max:64',
      'edit_lastname' => 'required|string|max:64',
      'edit_congregation_id' => 'required|not_in:0'
		]);

    $exists = $this->priest_service->updatePriestById($this->priest_id, $this->edit_firstname, $this->edit_middlename, $this->edit_lastname, $this->edit_congregation_id, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
			// $this->error('Failed to update record. Record does not exists.');
      $this->showErrorMessage = true;
		}
		else{
      // $this->success('Record updated successfully!');
      $this->showSuccessMessage = true;
		}

		// Optionally reset form fields after save
		$this->reset(['priest_id', 'priest_id']);
    $this->reset(['edit_firstname', 'edit_firstname']);
    $this->reset(['edit_middlename', 'edit_middlename']);
    $this->reset(['edit_lastname', 'edit_lastname']);
    $this->reset(['edit_congregation_id', 'edit_congregation_id']);

		// Close the modal
		$this->editPriestModal = false;

		$this->priest_lst();
	}

  public function openUpdatePriestStatusModal(int $priest_id, int $statuscode){
		$this->updatePriestStatusModal = true;
		$this->priest_id = $priest_id;
    $this->statuscode = $statuscode;
	}

  public function update_priest_status($priest_id, $statuscode){

    $result = $this->priest_service->updatePriestStatusById($priest_id, $statuscode, auth()->user()->id);
		
    if ($result[0]->result_id > 0) {
      $this->showSuccessMessage = true;
    }else{
      $this->showErrorMessage = true;
    }

		$this->updatePriestStatusModal = false;	
	}


	public function render(){
		return view('livewire.pages.priest.priest');
	}

}
