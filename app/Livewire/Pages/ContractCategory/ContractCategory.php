<?php

namespace App\Livewire\Pages\ContractCategory;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\ContractCategoryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class ContractCategory extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $contract_category_service;

  #public variables
  public $search;
  public $contract_category_id;
  public $statuscode;

  public $abbreviation;
  public $label;

  public $edit_abbreviation;
  public $edit_label;

  #modals
  public bool $addContractCategoryModal = false;
  public bool $editContractCategoryModal = false;
  public bool $updateContractCategoryStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		ContractCategoryService $contract_category_service,
	)
	{
		$this->contract_category_service = $contract_category_service;
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
	// public function load contract category records
	public function contract_category_lst(){
    try{
      if(!$this->search){
        $contract_category_lst = $this->contract_category_service->loadContractCategoryLst()->paginate(15);
        return $contract_category_lst;
      }else{
        $contract_category_lst = $this->contract_category_service->loadContractCategoryLstByKeyword($this->search)->paginate(15);
        return $contract_category_lst;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save contract category record changes
  public function save_contract_category(){
    try{
      // Validation and saving logic
      $this->validate([
        'abbreviation' => 'required|string|max:64',
        'label' => 'required|string|max:256'
      ]);

      $exists = $this->contract_category_service->addContractCategory($this->abbreviation, $this->label, auth()->user()->id);

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
      $this->reset(['abbreviation', 'abbreviation']);
      $this->reset(['label', 'label']);

      // Close the modal
      $this->addContractCategoryModal = false;

      $this->contract_category_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get contract category by id
	public function openEditContractCategoryModal(int $contract_category_id){
    try{
      $this->resetValidation();  // clears validation errors
      $this->editContractCategoryModal = true;
      $this->contract_category_id = $contract_category_id;

      $result = $this->contract_category_service->getContractCategoryById($this->contract_category_id);

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

  // public function save contract category record changes
  public function save_contract_category_record_changes(){
    try{
      // Validation and saving logic
      $this->validate([
        'edit_abbreviation' => 'required|string|max:64',
        'edit_label' => 'required|string|max:256'
      ]);

      $exists = $this->contract_category_service->updateContractCategoryById($this->contract_category_id, $this->edit_abbreviation, $this->edit_label, auth()->user()->id);

      if ($exists[0]->result_id == 0) {
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Updated contract category successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['contract_category_id', 'contract_category_id']);
      $this->reset(['edit_abbreviation', 'edit_abbreviation']);
      $this->reset(['edit_label', 'edit_label']);

      // Close the modal
      $this->editContractCategoryModal = false;

      $this->contract_category_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateContractCategoryStatusModal(int $contract_category_id, int $statuscode){
    try{
      $this->updateContractCategoryStatusModal = true;
      $this->contract_category_id = $contract_category_id;
      $this->statuscode = $statuscode;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_contract_category_status($contract_category_id, $statuscode){
    try{
      $result = $this->contract_category_service->updateContractCategoryStatusById($contract_category_id, $statuscode, auth()->user()->id);
      
      // // Toast
      if ($result[0]->result_id > 0) {
        // Optional: Show error to user
        $this->addMessage = 'Updated contract category status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }else{
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }

      $this->updateContractCategoryStatusModal = false;	
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}


	public function render(){
		return view('livewire.pages.contract-category.contract-category');
	}

}
