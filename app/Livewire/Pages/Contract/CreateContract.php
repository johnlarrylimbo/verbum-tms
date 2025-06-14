<?php

namespace App\Livewire\Pages\Contract;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\ContractService;
use App\Services\SelectOptionLibraryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class CreateContract extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $contract_service;

  #public variables
  public $search;
  public $contract_id;
  public $statuscode;

  public $contract_no_disabled, $contract_no, $client_id, $contract_category_id, $contract_type_id, $contract_detail_id, $contact_person, $contact_person_designation, $account_representative_id;
  public $contract_start, $contract_end, $contract_amount, $remarks;

  public $edit_client_id, $edit_contract_category_id;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

  public $contract_detail_options = [];

	public function boot(
		ContractService $contract_service,
    SelectOptionLibraryService $select_option_service,
	)
	{
		$this->contract_service = $contract_service;
    $this->select_option_service = $select_option_service;
	}

  public function mount(){
		// Initialize form fields
    $this->label = '';
    $this->client_id = 0;
    $this->contract_category_id = 0;
    $this->contract_type_id = 0;
    $this->contract_detail_id = 0;
    $this->contact_person = '';
    $this->contact_person_designation = '';
    $this->account_representative_id = 0;
    $this->contract_start = '';
    $this->contract_end = '';
    $this->contract_amount = '';
    $this->remarks = '';
	}

  public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

	public function generateContractNo()
	{
			// Example logic: "CON-" + current timestamp
			$this->contract_no_disabled = 'CON-' . now()->format('YmdHis');
			$this->contract_no = 'CON-' . now()->format('YmdHis');
	}

  #[Computed]
	// public function load client records options
	public function load_client_options(){
		return $this->select_option_service->loadClientOptions();
	}

  #[Computed]
	// public function load client records options
	public function load_contract_category_options(){
		return $this->select_option_service->loadContractCategoryOptions();
	}

  #[Computed]
	// public function load client records options
	public function load_contract_type_options(){
		return $this->select_option_service->loadContractTypeOptions();
	}

  #[Computed]
	// public function load client records options
	public function load_employee_options(){
		return $this->select_option_service->loadEmployeeOptions();
	}

  //public function load city or municipality when province change
  public function contractTypeChanged()
  {
    $contract_type_id = $this->contract_type_id;

    if (!$contract_type_id) {
        $this->contract_detail_options = [];
        return;
    }

    // Update the property that holds vicariate options
    $this->contract_detail_options = $this->select_option_service->loadContractDetailByContractTypeIdOptions($contract_type_id);
  }

  // public function save employee type record changes
  public function save_contract(){
		try{
			// Validation and saving logic
			$this->validate([
        'contract_no' => 'required|string|max:256',
        'client_id' => 'required|not_in:0',
        'contract_category_id' => 'required|not_in:0',
        'contract_type_id' => 'required|not_in:0',
        'contract_detail_id' => 'required|not_in:0',
				'contact_person' => 'required|string|max:256',
        'contact_person_designation' => 'required|string|max:256',
        'account_representative_id' => 'required|not_in:0',
        'contract_start' => 'required|date',
        'contract_end' => 'required|date|after_or_equal:contract_start',
        'contract_amount' => ['required', 'regex:/^\d+(\.\d{1,2})?$/']
			]);

			$exists = $this->contract_service->createContract($this->contract_no, $this->client_id, $this->contract_category_id, $this->contract_type_id, $this->contract_detail_id, $this->contact_person, $this->contact_person_designation, $this->account_representative_id, $this->contract_start, $this->contract_end, $this->contract_amount, $this->remarks, auth()->user()->id);

			if ($exists[0]->result_contract_no == $this->contract_no) {
				// Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Created new contract successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
      $this->reset(['contract_no_disabled', 'contract_no_disabled']);
			$this->reset(['contract_no', 'contract_no']);
      $this->reset(['client_id', 'client_id']);
      $this->reset(['contract_category_id', 'contract_category_id']);
      $this->reset(['contract_type_id', 'contract_type_id']);
      $this->reset(['contract_detail_id', 'contract_detail_id']);
      $this->reset(['contact_person', 'contact_person']);
      $this->reset(['contact_person_designation', 'contact_person_designation']);
      $this->reset(['account_representative_id', 'account_representative_id']);
      $this->reset(['contract_start', 'contract_start']);
      $this->reset(['contract_end', 'contract_end']);
      $this->reset(['contract_amount', 'contract_amount']);
      $this->reset(['remarks', 'remarks']);

		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

	public function render(){
		return view('livewire.pages.contract.create-contract');
	}

}
