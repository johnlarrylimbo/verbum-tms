<?php

namespace App\Livewire\Pages\ContractCategoryType;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\ContractCategoryTypeService;
use App\Services\SelectOptionLibraryService;
use App\Services\ContractCategoryService;
use App\Services\ContractTypeService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class ContractCategoryType extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $contract_category_type_service;
  protected $select_option_service;
  protected $contract_category_service;
  protected $contract_type_service;

  #public variables
  public $search;
  public $contract_category_type_id;
  public $statuscode;

  public $contract_category_id;
  public $contract_type_id;
  public $label;

  public $contract_category_abbreviation;
  public $contract_category_label;

  public $contract_type_label;

  public $edit_contract_category_id;
  public $edit_contract_type_id;
  public $edit_label;

  #modals
  public bool $addContractCategoryTypeModal = false;
  public bool $editContractCategoryTypeModal = false;
  public bool $updateContractCategoryTypeStatusModal = false;

  public bool $addContractCategoryModal = false;
  public bool $addContractTypeModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		ContractCategoryTypeService $contract_category_type_service,
    SelectOptionLibraryService $select_option_service,
    ContractCategoryService $contract_category_service,
    ContractTypeService $contract_type_service,
	)
	{
		$this->contract_category_type_service = $contract_category_type_service;
    $this->select_option_service = $select_option_service;
    $this->contract_category_service = $contract_category_service;
    $this->contract_type_service = $contract_type_service;
	}

  public function mount(){
		// Initialize form fields
    $this->contract_category_id = 0;
    $this->contract_type_id = 0;
    $this->label = '';
    $this->contract_category_abbreviation = '';
    $this->contract_category_label = '';
    $this->contract_type_label = '';
	}

  public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load contract category type records
	public function contract_category_type_lst(){
    try{
      if(!$this->search){
        $contract_category_type_lst = $this->contract_category_type_service->loadContractCategoryTypeLst()->paginate(15);
        return $contract_category_type_lst;
      }else{
        $contract_category_type_lst = $this->contract_category_type_service->loadContractCategoryTypeLstByKeyword($this->search)->paginate(15);
        return $contract_category_type_lst;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  #[Computed]
	// public function load contract category options
	public function load_contract_category_options(){
		return $this->select_option_service->loadContractCategoryOptions();
	}

  #[Computed]
	// public function load contract type options
	public function load_contract_type_options(){
		return $this->select_option_service->loadContractTypeOptions();
	}

  // public function save contract category type record changes
  public function save_contract_category_type(){
    try{
      // Validation and saving logic
      $this->validate([
        'contract_category_id' => 'required|not_in:0',
        'contract_type_id' => 'required|not_in:0',
        'label' => 'required|string|max:256'
      ]);

      $exists = $this->contract_category_type_service->addContractCategoryType($this->contract_category_id, $this->contract_type_id, $this->label, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new contract category type successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['contract_category_id', 'contract_category_id']);
      $this->reset(['contract_type_id', 'contract_type_id']);
      $this->reset(['label', 'label']);

      // Close the modal
      $this->addContractCategoryTypeModal = false;

      $this->contract_category_type_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get contract category type by id
	public function openEditContractCategoryTypeModal(int $contract_category_type_id){
    try{
      $this->resetValidation();  // clears validation errors
      $this->editContractCategoryTypeModal = true;
      $this->contract_category_type_id = $contract_category_type_id;

      $result = $this->contract_category_type_service->getContractCategoryTypeById($this->contract_category_type_id);

      foreach($result as $result){
        $this->edit_contract_category_id = $result->contract_category_id;
        $this->edit_contract_type_id = $result->contract_type_id;
        $this->edit_label = $result->label;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save contract category type record changes
  public function save_contract_category_type_record_changes(){
    try{
      // Validation and saving logic
      $this->validate([
        'edit_contract_category_id' => 'required|not_in:0',
        'edit_contract_type_id' => 'required|not_in:0',
        'edit_label' => 'required|string|max:256'
      ]);

      $exists = $this->contract_category_type_service->updateContractCategoryTypeById($this->contract_category_type_id, $this->edit_contract_category_id, $this->edit_contract_type_id, $this->edit_label, auth()->user()->id);

      if ($exists[0]->result_id == 0) {
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Updated contract category type successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['contract_category_type_id', 'contract_category_type_id']);
      $this->reset(['edit_contract_category_id', 'edit_contract_category_id']);
      $this->reset(['edit_contract_type_id', 'edit_contract_type_id']);
      $this->reset(['edit_label', 'edit_label']);

      // Close the modal
      $this->editContractCategoryTypeModal = false;

      $this->contract_category_type_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save contract category record changes
  public function save_contract_category(){
    try{
      // Validation and saving logic
      $this->validate([
        'contract_category_abbreviation' => 'required|string|max:64',
        'contract_category_label' => 'required|string|max:256'
      ]);

      $exists = $this->contract_category_service->addContractCategory($this->contract_category_abbreviation, $this->contract_category_label, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new contract category successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['contract_category_abbreviation', 'contract_category_abbreviation']);
      $this->reset(['contract_category_label', 'contract_category_label']);

      // Close the modal
      $this->addContractCategoryModal = false;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save contract type record changes
  public function save_contract_type(){
    try{
      // Validation and saving logic

      $this->validate([
        'contract_type_label' => 'required|string|max:256'
      ]);

      $exists = $this->contract_type_service->addContractType($this->contract_type_label, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new contract type successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['contract_type_label', 'contract_type_label']);

      // Close the modal
      $this->addContractTypeModal = false;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateContractCategoryTypeStatusModal(int $contract_category_type_id, int $statuscode){
    try{
      $this->updateContractCategoryTypeStatusModal = true;
      $this->contract_category_type_id = $contract_category_type_id;
      $this->statuscode = $statuscode;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_contract_category_type_status($contract_category_type_id, $statuscode){
    try{
      $result = $this->contract_category_type_service->updateContractCategoryTypeStatusById($contract_category_type_id, $statuscode, auth()->user()->id);
      
      // // Toast
      if ($result[0]->result_id > 0) {
        // Optional: Show error to user
        $this->addMessage = 'Updated contract category type status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }else{
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }

      $this->updateContractCategoryTypeStatusModal = false;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }	
	}


	public function render(){
		return view('livewire.pages.contract-category-type.contract-category-type');
	}

}
