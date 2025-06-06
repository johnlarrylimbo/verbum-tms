<?php

namespace App\Livewire\Pages\RegionalCenter;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\RegionalCenterService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class RegionalCenter extends Component
{

	use WithPagination;
	use Toast;

	#protected variables
	protected $regional_center_service;

  #public variables
  public $search;
  public $regional_center_id;
  public $statuscode;

  public $abbreviation;
  public $label;

  public $edit_abbreviation;
  public $edit_label;

  #modals
  public bool $addRegionalCenterModal = false;
  public bool $editRegionalCenterModal = false;
  public bool $updateRegionalCenterStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		RegionalCenterService $regional_center_service,
	)
	{
		$this->regional_center_service = $regional_center_service;
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
	// public function load regional center records
	public function regional_center_lst(){
    try{
      if(!$this->search){
        $regional_center_lst = $this->regional_center_service->loadRegionalCenterLst()->paginate(15);
        return $regional_center_lst;
      }else{
        $regional_center_lst = $this->regional_center_service->loadRegionalCenterLstByKeyword($this->search)->paginate(15);
        return $regional_center_lst;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save regional center record changes
  public function save_regional_center(){
    try{
      // Validation and saving logic
      $this->validate([
        'abbreviation' => 'required|string|max:64',
        'label' => 'required|string|max:255',
      ]);

      $exists = $this->regional_center_service->addRegionalCenter($this->abbreviation, $this->label, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new regional center successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['abbreviation', 'abbreviation']);
      $this->reset(['label', 'label']);

      // Close the modal
      $this->addRegionalCenterModal = false;

      $this->regional_center_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get island group by id
	public function openEditRegionalCenterModal(int $regional_center_id){
    try{
      $this->resetValidation();  // clears validation errors
      $this->editRegionalCenterModal = true;
      $this->regional_center_id = $regional_center_id;

      $result = $this->regional_center_service->getRegionalCenterById($this->regional_center_id);

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

  // public function save island group record changes
  public function save_regional_center_record_changes(){
    try{
      // Validation and saving logic
      $this->validate([
        'edit_abbreviation' => 'required|string|max:64',
        'edit_label' => 'required|string|max:255'
      ]);

      $exists = $this->regional_center_service->updateRegionalCenterById($this->regional_center_id, $this->edit_abbreviation, $this->edit_label, auth()->user()->id);

      if ($exists[0]->result_id == 0) {
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Updated regional center successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['regional_center_id', 'regional_center_id']);
      $this->reset(['edit_abbreviation', 'edit_abbreviation']);
      $this->reset(['edit_label', 'edit_label']);

      // Close the modal
      $this->editRegionalCenterModal = false;

      $this->regional_center_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateRegionalCenterStatusModal(int $regional_center_id, int $statuscode){
    try{
      $this->updateRegionalCenterStatusModal = true;
      $this->regional_center_id = $regional_center_id;
      $this->statuscode = $statuscode;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_regional_center_status($regional_center_id, $statuscode){
    try{
      $result = $this->regional_center_service->updateRegionalCenterStatusById($regional_center_id, $statuscode, auth()->user()->id);
      
      // // Toast
      if ($result[0]->result_id > 0) {
        // Optional: Show error to user
        $this->addMessage = 'Updated regional center status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }else{
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }

      $this->updateRegionalCenterStatusModal = false;	
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}


	public function render(){
		return view('livewire.pages.regional-center.regional-center');
	}

}
