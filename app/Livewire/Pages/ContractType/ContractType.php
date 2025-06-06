<?php

namespace App\Livewire\Pages\ContractType;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\ContractTypeService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class ContractType extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $contract_type_service;

  #public variables
  public $search;
  public $contract_type_id;
  public $statuscode;

  public $label;

  public $edit_label;

  #modals
  public bool $addContractTypeModal = false;
  public bool $editContractTypeModal = false;
  public bool $updateContractTypeStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		ContractTypeService $contract_type_service,
	)
	{
		$this->contract_type_service = $contract_type_service;
	}

  public function mount(){
		// Initialize form fields
    $this->label = '';
	}

  public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load contract type records
	public function contract_type_lst(){
		try{
			if(!$this->search){
				$contract_type_lst = $this->contract_type_service->loadContractTypeLst()->paginate(15);
				return $contract_type_lst;
			}else{
				$contract_type_lst = $this->contract_type_service->loadContractTypeLstByKeyword($this->search)->paginate(15);
				return $contract_type_lst;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save contract type record changes
  public function save_contract_type(){
		try{
			// Validation and saving logic
			$this->validate([
				'label' => 'required|string|max:256'
			]);

			$exists = $this->contract_type_service->addContractType($this->label, auth()->user()->id);

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
			$this->reset(['label', 'label']);

			// Close the modal
			$this->addContractTypeModal = false;

			$this->contract_type_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get contract type by id
	public function openEditContractTypeModal(int $contract_type_id){
		try{
			$this->resetValidation();  // clears validation errors
			$this->editContractTypeModal = true;
			$this->contract_type_id = $contract_type_id;

			$result = $this->contract_type_service->getContractTypeById($this->contract_type_id);

			foreach($result as $result){
				$this->edit_label = $result->label;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save contract type record changes
  public function save_contract_type_record_changes(){
		try{
			// Validation and saving logic
			$this->validate([
				'edit_label' => 'required|string|max:256'
			]);

			$exists = $this->contract_type_service->updateContractTypeById($this->contract_type_id, $this->edit_label, auth()->user()->id);

			if ($exists[0]->result_id == 0) {
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Updated contract type successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['contract_type_id', 'contract_type_id']);
			$this->reset(['edit_label', 'edit_label']);

			// Close the modal
			$this->editContractTypeModal = false;

			$this->contract_type_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateContractTypeStatusModal(int $contract_type_id, int $statuscode){
		try{
			$this->updateContractTypeStatusModal = true;
			$this->contract_type_id = $contract_type_id;
			$this->statuscode = $statuscode;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_contract_type_status($contract_type_id, $statuscode){
		try{
			$result = $this->contract_type_service->updateContractTypeStatusById($contract_type_id, $statuscode, auth()->user()->id);
			
			// // Toast
			if ($result[0]->result_id > 0) {
				// Optional: Show error to user
        $this->addMessage = 'Updated contract type status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}else{
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}

			$this->updateContractTypeStatusModal = false;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }	
	}


	public function render(){
		return view('livewire.pages.contract-type.contract-type');
	}

}
