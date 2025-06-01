<?php

namespace App\Livewire\Pages\Parish;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\ParishService;
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

  public $showAddSuccessMessage = false;
  public $showAddErrorMessage = false;
  public $showSuccessMessage = false;
  public $showErrorMessage = false;

	public function boot(
		ParishService $parish_service,
    SelectOptionLibraryService $select_option_service,
	)
	{
		$this->parish_service = $parish_service;
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

  // #[Computed]
	// // public function load diocese options
	// public function load_diocese_options(){
	// 	return $this->select_option_service->loadDioceseOptions();
	// }

  // // public function save diocese record changes
  // public function save(){
	// 	// Validation and saving logic

	// 	$this->validate([
  //     'diocese_id' => 'required|not_in:0',
  //     'label' => 'required|string|max:256'
	// 	]);

  //   $exists = $this->diocese_vicariate_service->addVicariate($this->diocese_id, $this->label, auth()->user()->id);

	// 	if ($exists[0]->result_id == 0) {
  //     $this->showAddErrorMessage = true;
	// 	}
	// 	else{
  //     $this->showAddSuccessMessage = true;
	// 	}

	// 	// Optionally reset form fields after save
  //   $this->reset(['diocese_id', 'diocese_id']);
	// 	$this->reset(['label', 'label']);

	// 	// Close the modal
	// 	$this->addVicariateModal = false;

	// 	$this->vicariate_lst();
	// }

  // // public function get vicariate by id
	// public function openEditVicariateModal(int $vicariate_id){
  //   $this->resetValidation();  // clears validation errors
	// 	$this->editVicariateModal = true;
	// 	$this->vicariate_id = $vicariate_id;

  //   $result = $this->diocese_vicariate_service->getVicariateById($this->vicariate_id);

	// 	foreach($result as $result){
  //     $this->edit_label = $result->label;
  //     $this->edit_diocese_id = $result->diocese_id;
	// 	}
	// }

  // // public function save vicariate record changes
  // public function save_vicariate_record_changes(){
	// 	// Validation and saving logic

	// 	$this->validate([
  //     'edit_label' => 'required|string|max:256',
  //     'edit_diocese_id' => 'required|not_in:0'
	// 	]);

  //   $exists = $this->diocese_vicariate_service->updateVicariateById($this->vicariate_id, $this->edit_label, $this->edit_diocese_id, auth()->user()->id);

	// 	if ($exists[0]->result_id == 0) {
  //     $this->showErrorMessage = true;
	// 	}
	// 	else{
  //     $this->showSuccessMessage = true;
	// 	}

	// 	// Optionally reset form fields after save
	// 	$this->reset(['vicariate_id', 'vicariate_id']);
  //   $this->reset(['edit_label', 'edit_label']);
	// 	$this->reset(['edit_diocese_id', 'edit_diocese_id']);

	// 	// Close the modal
	// 	$this->editVicariateModal = false;

	// 	$this->vicariate_lst();
	// }

  // public function openUpdateVicariateStatusModal(int $vicariate_id, int $statuscode){
	// 	$this->updateVicariateStatusModal = true;
	// 	$this->vicariate_id = $vicariate_id;
  //   $this->statuscode = $statuscode;
	// }

  // public function update_vicariate_status($vicariate_id, $statuscode){

  //   $result = $this->diocese_vicariate_service->updateVicariateStatusById($vicariate_id, $statuscode, auth()->user()->id);
		
	// 	// // Toast
  //   if ($result[0]->result_id > 0) {
  //     $this->showSuccessMessage = true;
  //   }else{
  //     $this->showErrorMessage = true;
  //   }

	// 	$this->updateVicariateStatusModal = false;	
	// }


	public function render(){
		return view('livewire.pages.parish.parish');
	}

}
