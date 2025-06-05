<?php

namespace App\Livewire\Pages\ClientProfiling;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\ClientProfilingService;
use App\Services\SelectOptionLibraryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class ClientProfiling extends Component
{

	use WithPagination;
	use Toast;

	#protected variables
	protected $client_profiling_service;
  protected $select_option_service;

  #public variables
  public $search;
  public $client_id;
  public $statuscode;

  public $client_name;
  public $birthdate;
  public $country_id;
  public $region_id;

  public $edit_client_name;

  #modals
  // public bool $addRoleModal = false;
  // public bool $editRoleModal = false;
  // public bool $updateRoleStatusModal = false;

  public $showAddSuccessMessage = false;
  public $showAddErrorMessage = false;
  public $showSuccessMessage = false;
  public $showErrorMessage = false;

  public $province_options = [];

	public function boot(
		ClientProfilingService $client_profiling_service,
    SelectOptionLibraryService $select_option_service,
	)
	{
		$this->client_profiling_service = $client_profiling_service;
    $this->select_option_service = $select_option_service;
	}

	public function mount(){
		// Initialize form fields
		$this->client_name = '';
    $this->birthdate = '';
    $this->country_id = 0;
    $this->region_id = 0;
	}

	public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load country options
	public function load_country_options(){
		return $this->select_option_service->loadCountryOptions();
	}

  #[Computed]
	// public function load region options
	public function load_region_options(){
		return $this->select_option_service->loadRegionOptions();
	}

  //public function load provinces when region change
  public function regionChanged()
  {
    $region_id = $this->region_id;

    if (!$region_id) {
        $this->province_options = [];
        return;
    }

    // Update the property that holds vicariate options
    $this->province_options = $this->select_option_service->loadProvincesByRegionIdOptions($region_id);
  }

  // // public function save role record changes
  // public function save_role(){
	// 	// Validation and saving logic

	// 	$this->validate([
	// 		'abbreviation' => 'required|string|max:255',
  //     'label' => 'required|string|max:255'
	// 	]);

  //   $exists = $this->role_service->addRole($this->abbreviation, $this->label, auth()->user()->id);

	// 	if ($exists[0]->result_id == 0) {
  //     $this->showAddErrorMessage = true;
	// 	}
	// 	else{
  //     $this->showAddSuccessMessage = true;
	// 	}

	// 	// Optionally reset form fields after save
  //   $this->reset(['abbreviation', 'abbreviation']);
	// 	$this->reset(['label', 'label']);

	// 	// Close the modal
	// 	$this->addRoleModal = false;

	// 	$this->role_lst();
	// }

  // // public function get role by id
	// public function openEditRoleModal(int $role_id){
  //   $this->resetValidation();  // clears validation errors
	// 	$this->editRoleModal = true;
	// 	$this->role_id = $role_id;

  //   $result = $this->role_service->getRoleById($this->role_id);

	// 	foreach($result as $result){
	// 		$this->edit_abbreviation = $result->abbreviation;
  //     $this->edit_label = $result->label;
	// 	}
	// }

  // // public function save role record changes
  // public function save_role_record_changes(){
	// 	// Validation and saving logic
  //   $this->validate([
	// 		'edit_abbreviation' => 'required|string|max:255',
  //     'edit_label' => 'required|string|max:255'
	// 	]);

  //   $exists = $this->role_service->updateRoleById($this->role_id, $this->edit_abbreviation, $this->edit_label, auth()->user()->id);

	// 	if ($exists[0]->result_id == 0) {
  //     $this->showErrorMessage = true;
	// 	}
	// 	else{
  //     $this->showSuccessMessage = true;
	// 	}

	// 	// Optionally reset form fields after save
  //   $this->reset(['role_id', 'role_id']);
	// 	$this->reset(['edit_abbreviation', 'edit_abbreviation']);
  //   $this->reset(['edit_label', 'edit_label']);

	// 	// Close the modal
	// 	$this->editRoleModal = false;

	// 	$this->role_lst();
	// }

  // public function openUpdateRoleStatusModal(int $role_id, int $statuscode){
	// 	$this->updateRoleStatusModal = true;
	// 	$this->role_id = $role_id;
  //   $this->statuscode = $statuscode;
	// }

  // public function update_role_status($role_id, $statuscode){

  //   $result = $this->role_service->updateRoleStatusById($role_id, $statuscode, auth()->user()->id);
		
	// 	// // Toast
  //   if ($result[0]->result_id > 0) {
  //     $this->showSuccessMessage = true;
  //   }else{
  //     $this->showErrorMessage = true;
  //   }

	// 	$this->updateRoleStatusModal = false;	
	// }


	public function render(){
		return view('livewire.pages.client-profiling.client-profiling');
	}

}
