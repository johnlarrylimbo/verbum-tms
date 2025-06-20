<?php

namespace App\Livewire\Pages\Region;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\RegionService;
use App\Services\SelectOptionLibraryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class Region extends Component
{

	use WithPagination;
	use Toast;

	#protected variables
	protected $region_service;
  protected $select_option_service;

  #public variables
  public $search;
  public $region_id;
  public $statuscode;

  public $island_group_id;
  public $regional_center_id;
  public $abbreviation;
  public $numerals;
  public $label;

  public $edit_island_group_id;
  public $edit_regional_center_id;
  public $edit_abbreviation;
  public $edit_numerals;
  public $edit_label;

  #modals
  public bool $addRegionModal = false;
  public bool $editRegionModal = false;
  public bool $updateRegionStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		RegionService $region_service,
    SelectOptionLibraryService $select_option_service,
	)
	{
		$this->region_service = $region_service;
    $this->select_option_service = $select_option_service;
	}

	public function mount(){
		// Initialize form fields
    $this->island_group_id = 0;
    $this->regional_center_id = 0;
    $this->abbreviation = '';
    $this->numerals = '';
    $this->label = '';
	}

	public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load region records
	public function region_lst(){
    try{
      if(!$this->search){
        $region_lst = $this->region_service->loadRegionLst()->paginate(15);
        return $region_lst;
      }else{
        $region_lst = $this->region_service->loadRegionLstByKeyword($this->search)->paginate(15);
        return $region_lst;
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
	// public function load regional center options
	public function load_regional_center_options(){
		return $this->select_option_service->loadRegionalCenterOptions();
	}

  // public function save region record changes
  public function save_region(){
    try{
      // Validation and saving logic
      $this->validate([
        'island_group_id' => 'required|not_in:0',
        'regional_center_id' => 'required|not_in:0',
        'numerals' => 'required|string|max:25',
        'abbreviation' => 'required|string|max:64',
        'label' => 'required|string|max:255',
      ]);

      $exists = $this->region_service->addRegion($this->island_group_id, $this->regional_center_id, $this->numerals, $this->abbreviation, $this->label, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new region successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['island_group_id', 'island_group_id']);
      $this->reset(['regional_center_id', 'regional_center_id']);
      $this->reset(['numerals', 'numerals']);
      $this->reset(['abbreviation', 'abbreviation']);
      $this->reset(['label', 'label']);

      // Close the modal
      $this->addRegionModal = false;

      $this->region_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get region by id
	public function openEditRegionModal(int $region_id){
    try{
      $this->resetValidation();  // clears validation errors
      $this->editRegionModal = true;
      $this->region_id = $region_id;

      $result = $this->region_service->getRegionById($this->region_id);

      foreach($result as $result){
        $this->edit_island_group_id = $result->island_group_id;
        $this->edit_regional_center_id = $result->regional_center_id;
        $this->edit_numerals = $result->numerals;
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

  // public function save region record changes
  public function save_region_record_changes(){
    try{
      // Validation and saving logic
      $this->validate([
        'edit_island_group_id' => 'required|not_in:0',
        'edit_regional_center_id' => 'required|not_in:0',
        'edit_numerals' => 'required|string|max:25',
        'edit_abbreviation' => 'required|string|max:64',
        'edit_label' => 'required|string|max:255',
      ]);

      $exists = $this->region_service->updateRegionById($this->region_id, $this->edit_island_group_id, $this->edit_regional_center_id, $this->edit_numerals, $this->edit_abbreviation, $this->edit_label, auth()->user()->id);

      if ($exists[0]->result_id == 0) {
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Updated region successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['region_id', 'region_id']);
      $this->reset(['edit_island_group_id', 'edit_island_group_id']);
      $this->reset(['edit_regional_center_id', 'edit_regional_center_id']);
      $this->reset(['edit_numerals', 'edit_numerals']);
      $this->reset(['edit_abbreviation', 'edit_abbreviation']);
      $this->reset(['edit_label', 'edit_label']);

      // Close the modal
      $this->editRegionModal = false;

      $this->region_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateRegionStatusModal(int $region_id, int $statuscode){
    try{
      $this->updateRegionStatusModal = true;
      $this->region_id = $region_id;
      $this->statuscode = $statuscode;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_region_status($region_id, $statuscode){
    try{
      $result = $this->region_service->updateRegionStatusById($region_id, $statuscode, auth()->user()->id);
      
      // // Toast
      if ($result[0]->result_id > 0) {
        // Optional: Show error to user
        $this->addMessage = 'Updated region status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }else{
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }

      $this->updateRegionStatusModal = false;	
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}


	public function render(){
		return view('livewire.pages.region.region');
	}

}
