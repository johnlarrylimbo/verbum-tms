<?php

namespace App\Livewire\Pages\Province;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\ProvinceService;
use App\Services\SelectOptionLibraryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class Province extends Component
{

	use WithPagination;
	use Toast;

	#protected variables
	protected $province_service;
  protected $select_option_service;

  #public variables
  public $search;
  public $province_id;
  public $statuscode;

  public $island_group_id;
  public $region_id;
  public $label;

  public $edit_island_group_id;
  public $edit_region_id;
  public $edit_label;

  #modals
  public bool $addProvinceModal = false;
  public bool $editProvinceModal = false;
  public bool $updateProvinceStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

  public $province_options = [];

	public function boot(
		ProvinceService $province_service,
    SelectOptionLibraryService $select_option_service,
	)
	{
		$this->province_service = $province_service;
    $this->select_option_service = $select_option_service;
	}

	public function mount(){
		// Initialize form fields
    $this->island_group_id = 0;
    $this->region_id = 0;
    $this->label = '';
	}

	public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load province records
	public function province_lst(){
		try{
			if(!$this->search){
				$province_lst = $this->province_service->loadProvinceLst()->paginate(15);
				return $province_lst;
			}else{
				$province_lst = $this->province_service->loadProvinceLstByKeyword($this->search)->paginate(15);
				return $province_lst;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  #[Computed]
	// public function load island group options
	public function load_island_group_options(){
		return $this->select_option_service->loadIslandGroupOptions();
	}

  #[Computed]
	// public function load region options
	public function load_region_options(){
		return $this->select_option_service->loadRegionOptions();
	}

  // public function save province record changes
  public function save_province(){
		try{
			// Validation and saving logic
			$this->validate([
				'label' => 'required|string|max:255',
				'island_group_id' => 'required|not_in:0',
				'region_id' => 'required|not_in:0'
			]);

			$exists = $this->province_service->addProvince($this->label, $this->island_group_id, $this->region_id, auth()->user()->id);

			if ($exists[0]->result_id == 1) {
				// Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Added new province successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['label', 'label']);
			$this->reset(['island_group_id', 'island_group_id']);
			$this->reset(['region_id', 'region_id']);

			// Close the modal
			$this->addProvinceModal = false;

			$this->province_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get province by id
	public function openEditProvinceModal(int $province_id){
		try{
			$this->resetValidation();  // clears validation errors
			$this->editProvinceModal = true;
			$this->province_id = $province_id;

			$result = $this->province_service->getProvinceById($this->province_id);

			foreach($result as $result){
				$this->edit_label = $result->label;
				$this->edit_island_group_id = $result->island_group_id;
				$this->edit_region_id = $result->region_id;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save province record changes
  public function save_province_record_changes(){
		try{
			// Validation and saving logic
			$this->validate([
				'edit_label' => 'required|string|max:255',
				'edit_island_group_id' => 'required|not_in:0',
				'edit_region_id' => 'required|not_in:0'
			]);

			$exists = $this->province_service->updateProvinceById($this->province_id, $this->edit_label, $this->edit_island_group_id, $this->edit_region_id, auth()->user()->id);

			if ($exists[0]->result_id == 0) {
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Updated province successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['province_id', 'province_id']);
			$this->reset(['edit_label', 'edit_label']);
			$this->reset(['edit_island_group_id', 'edit_island_group_id']);
			$this->reset(['edit_region_id', 'edit_region_id']);

			// Close the modal
			$this->editProvinceModal = false;

			$this->province_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateProvinceStatusModal(int $province_id, int $statuscode){
		try{
			$this->updateProvinceStatusModal = true;
			$this->province_id = $province_id;
			$this->statuscode = $statuscode;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_province_status($province_id, $statuscode){
		try{
			$result = $this->province_service->updateProvinceStatusById($province_id, $statuscode, auth()->user()->id);
			
			// // Toast
			if ($result[0]->result_id > 0) {
				// Optional: Show error to user
        $this->addMessage = 'Updated province status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}else{
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}

			$this->updateProvinceStatusModal = false;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }	
	}


	public function render(){
		return view('livewire.pages.province.province');
	}

}
