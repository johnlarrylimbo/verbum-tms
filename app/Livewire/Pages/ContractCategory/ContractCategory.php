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

  public $showAddSuccessMessage = false;
  public $showAddErrorMessage = false;
  public $showSuccessMessage = false;
  public $showErrorMessage = false;

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
    if(!$this->search){
      $contract_category_lst = $this->contract_category_service->loadContractCategoryLst()->paginate(15);
		  return $contract_category_lst;
    }else{
      $contract_category_lst = $this->contract_category_service->loadContractCategoryLstByKeyword($this->search)->paginate(15);
		  return $contract_category_lst;
    }
	}

  // public function save contract category record changes
  public function save(){
		// Validation and saving logic

		$this->validate([
      'abbreviation' => 'required|string|max:64',
      'label' => 'required|string|max:256'
		]);

    $exists = $this->contract_category_service->addContractCategory($this->abbreviation, $this->label, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
      $this->showAddErrorMessage = true;
		}
		else{
      $this->showAddSuccessMessage = true;
		}

		// Optionally reset form fields after save
    $this->reset(['abbreviation', 'abbreviation']);
    $this->reset(['label', 'label']);

		// Close the modal
		$this->addContractCategoryModal = false;

		$this->contract_category_lst();
	}

  // public function get contract category by id
	public function openEditContractCategoryModal(int $contract_category_id){
    $this->resetValidation();  // clears validation errors
		$this->editContractCategoryModal = true;
		$this->contract_category_id = $contract_category_id;

    $result = $this->contract_category_service->getContractCategoryById($this->contract_category_id);

		foreach($result as $result){
      $this->edit_abbreviation = $result->abbreviation;
      $this->edit_label = $result->label;
		}
	}

  // public function save contract category record changes
  public function save_contract_category_record_changes(){
		// Validation and saving logic
    $this->validate([
      'edit_abbreviation' => 'required|string|max:64',
      'edit_label' => 'required|string|max:256'
		]);

    $exists = $this->contract_category_service->updateContractCategoryById($this->contract_category_id, $this->edit_abbreviation, $this->edit_label, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
      $this->showErrorMessage = true;
		}
		else{
      $this->showSuccessMessage = true;
		}

		// Optionally reset form fields after save
    $this->reset(['contract_category_id', 'contract_category_id']);
    $this->reset(['edit_abbreviation', 'edit_abbreviation']);
    $this->reset(['edit_label', 'edit_label']);

		// Close the modal
		$this->editContractCategoryModal = false;

		$this->contract_category_lst();
	}

  public function openUpdateContractCategoryStatusModal(int $contract_category_id, int $statuscode){
		$this->updateContractCategoryStatusModal = true;
		$this->contract_category_id = $contract_category_id;
    $this->statuscode = $statuscode;
	}

  public function update_contract_category_status($contract_category_id, $statuscode){

    $result = $this->contract_category_service->updateContractCategoryStatusById($contract_category_id, $statuscode, auth()->user()->id);
		
		// // Toast
    if ($result[0]->result_id > 0) {
      $this->showSuccessMessage = true;
    }else{
      $this->showErrorMessage = true;
    }

		$this->updateContractCategoryStatusModal = false;	
	}


	public function render(){
		return view('livewire.pages.contract-category.contract-category');
	}

}
