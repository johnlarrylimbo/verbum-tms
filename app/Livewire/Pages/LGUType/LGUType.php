<?php

namespace App\Livewire\Pages\LGUType;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\LGUTypeService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class LGUType extends Component
{

	use WithPagination;
	use Toast;

	#protected variables
	protected $lgu_type_service;

  #public variables
  public $search;
  public $lgu_type_id;
  public $statuscode;

  public $abbreviation;
  public $label;

  public $edit_abbreviation;
  public $edit_label;

  #modals
  public bool $addLGUTypeModal = false;
  public bool $editLGUTypeModal = false;
  public bool $updateLGUTypeStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		LGUTypeService $lgu_type_service,
	)
	{
		$this->lgu_type_service = $lgu_type_service;
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
	// public function load lgu type records
	public function lgu_type_lst(){
    try{
      if(!$this->search){
        $lgu_type_lst = $this->lgu_type_service->loadLGUTypeLst()->paginate(15);
        return $lgu_type_lst;
      }else{
        $lgu_type_lst = $this->lgu_type_service->loadLGUTypeLstByKeyword($this->search)->paginate(15);
        return $lgu_type_lst;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save lgu type record changes
  public function save_lgu_type(){
    try{
      // Validation and saving logic
      $this->validate([
        'abbreviation' => 'required|string|max:64',
        'label' => 'required|string|max:255',
      ]);

      $exists = $this->lgu_type_service->addLGUType($this->abbreviation, $this->label, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new LGU Type successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['abbreviation', 'abbreviation']);
      $this->reset(['label', 'label']);

      // Close the modal
      $this->addLGUTypeModal = false;

      $this->lgu_type_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get lgu type by id
	public function openEditLGUTypeModal(int $lgu_type_id){
    try{
      $this->resetValidation();  // clears validation errors
      $this->editLGUTypeModal = true;
      $this->lgu_type_id = $lgu_type_id;

      $result = $this->lgu_type_service->getLGUTypeById($this->lgu_type_id);

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

  // public function save lgu type record changes
  public function save_lgu_type_record_changes(){
    try{
      // Validation and saving logic
      $this->validate([
        'edit_abbreviation' => 'required|string|max:64',
        'edit_label' => 'required|string|max:255',
      ]);

      $exists = $this->lgu_type_service->updateLGUTypeById($this->lgu_type_id, $this->edit_abbreviation, $this->edit_label, auth()->user()->id);

      if ($exists[0]->result_id == 0) {
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Updated LGU Type successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['lgu_type_id', 'lgu_type_id']);
      $this->reset(['edit_abbreviation', 'edit_abbreviation']);
      $this->reset(['edit_label', 'edit_label']);

      // Close the modal
      $this->editLGUTypeModal = false;

      $this->lgu_type_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateLGUTypeStatusModal(int $lgu_type_id, int $statuscode){
    try{
      $this->updateLGUTypeStatusModal = true;
      $this->lgu_type_id = $lgu_type_id;
      $this->statuscode = $statuscode;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_lgu_type_status($lgu_type_id, $statuscode){
    try{
      $result = $this->lgu_type_service->updateLGUTypeStatusById($lgu_type_id, $statuscode, auth()->user()->id);
      
      // // Toast
      if ($result[0]->result_id > 0) {
        // Optional: Show error to user
        $this->addMessage = 'Updated LGU Type status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }else{
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }

      $this->updateLGUTypeStatusModal = false;	
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}


	public function render(){
		return view('livewire.pages.lgu-type.lgu-type');
	}

}
