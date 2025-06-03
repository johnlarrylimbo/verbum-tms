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

  public $showAddSuccessMessage = false;
  public $showAddErrorMessage = false;
  public $showSuccessMessage = false;
  public $showErrorMessage = false;

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
    if(!$this->search){
      $contract_type_lst = $this->contract_type_service->loadContractTypeLst()->paginate(15);
		  return $contract_type_lst;
    }else{
      $contract_type_lst = $this->contract_type_service->loadContractTypeLstByKeyword($this->search)->paginate(15);
		  return $contract_type_lst;
    }
	}

  // public function save contract type record changes
  public function save(){
		// Validation and saving logic

		$this->validate([
      'label' => 'required|string|max:256'
		]);

    $exists = $this->contract_type_service->addContractType($this->label, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
      $this->showAddErrorMessage = true;
		}
		else{
      $this->showAddSuccessMessage = true;
		}

		// Optionally reset form fields after save
    $this->reset(['label', 'label']);

		// Close the modal
		$this->addContractTypeModal = false;

		$this->contract_type_lst();
	}

  // public function get contract type by id
	public function openEditContractTypeModal(int $contract_type_id){
    $this->resetValidation();  // clears validation errors
		$this->editContractTypeModal = true;
		$this->contract_type_id = $contract_type_id;

    $result = $this->contract_type_service->getContractTypeById($this->contract_type_id);

		foreach($result as $result){
      $this->edit_label = $result->label;
		}
	}

  // public function save contract type record changes
  public function save_contract_type_record_changes(){
		// Validation and saving logic
    $this->validate([
      'edit_label' => 'required|string|max:256'
		]);

    $exists = $this->contract_type_service->updateContractTypeById($this->contract_type_id, $this->edit_label, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
      $this->showErrorMessage = true;
		}
		else{
      $this->showSuccessMessage = true;
		}

		// Optionally reset form fields after save
    $this->reset(['contract_type_id', 'contract_type_id']);
    $this->reset(['edit_label', 'edit_label']);

		// Close the modal
		$this->editContractTypeModal = false;

		$this->contract_type_lst();
	}

  public function openUpdateContractTypeStatusModal(int $contract_type_id, int $statuscode){
		$this->updateContractTypeStatusModal = true;
		$this->contract_type_id = $contract_type_id;
    $this->statuscode = $statuscode;
	}

  public function update_contract_type_status($contract_type_id, $statuscode){

    $result = $this->contract_type_service->updateContractTypeStatusById($contract_type_id, $statuscode, auth()->user()->id);
		
		// // Toast
    if ($result[0]->result_id > 0) {
      $this->showSuccessMessage = true;
    }else{
      $this->showErrorMessage = true;
    }

		$this->updateContractTypeStatusModal = false;	
	}


	public function render(){
		return view('livewire.pages.contract-type.contract-type');
	}

}
