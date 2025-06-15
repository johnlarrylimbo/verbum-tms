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
class Contract extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $contract_service;
	protected $select_option_service;

  #public variables
  public $search;
  public $contract_id;
  public $statuscode;

  public $contract_no_disabled, $client_name, $contract_category_label, $contract_type_label, $contract_category_type_detail_label, $account_amount;
	public $amount_to_be_paid, $payment_type_id, $amount, $check_bank, $check_no, $receipt_remarks, $balance;

  public $edit_client_id, $edit_contract_category_id ;

  #modals
  public bool $updateContractStatusModal = false;
	public bool $showDrawer2 = false;
	public $show_check_no = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

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
    $this->contract_no_disabled = '';
		$this->client_name = '';
		$this->contract_category_label = '';
		$this->contract_type_label = '';
		$this->contract_category_type_detail_label = '';
		$this->account_amount = '';
		$this->amount = '';
		$this->payment_type_id = 0;
		$this->check_bank = '';
		$this->check_no = '';
		$this->receipt_remarks = '';
		$this->balance = '';
	}

  public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load contract records
	public function contract_lst(){
		try{
			if(!$this->search){
				$contract_lst = $this->contract_service->loadContractLst()->paginate(15);
				return $contract_lst;
			}else{
				$contract_lst = $this->contract_service->loadContractLstByKeyword($this->search)->paginate(15);
				return $contract_lst;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

	public function generateContractNo()
	{
			// Example logic: "CON-" + current timestamp
			$this->contract_no_disabled = 'CON-' . now()->format('YmdHis');
			$this->contract_no = 'CON-' . now()->format('YmdHis');
	}

	public function openCreateContractWindow()
	{ 
		$this->redirect(route('contract.create_new_contract'));
	}

	#[Computed]
	// public function load client records options
	public function load_payment_type_options(){
		return $this->select_option_service->loadPaymentTypeOptions();
	}

	public function openContractPaymentWindow($contract_id)
	{ 
		try{
			$this->resetValidation();  // clears validation errors
			$this->showDrawer2 = !$this->showDrawer2;
			$this->contract_id = $contract_id;

			$result = $this->contract_service->getContractForPaymentById($contract_id);

			foreach($result as $result){
				$this->contract_no_disabled = $result->contract_account_no;
				$this->client_name = $result->client_name;
				$this->contract_category_label = $result->contract_category_label;
				$this->contract_type_label = $result->contract_type_label;
				$this->contract_category_type_detail_label = $result->contract_category_type_detail_label;
				$this->account_amount = $result->amount;
				$this->balance = $result->balance;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

	public function paymentTypeChanged()
	{
		$this->show_check_no = $this->payment_type_id == 2;
	}

  // public function save employee type record changes
  public function save_payment(){
		try{
			// Validation and saving logic
			$this->validate([
				'payment_type_id' => 'required|not_in:0',
				'amount' => ['required', 'regex:/^\d+(\.\d{1,2})?$/']
			]);

			if($this->payment_type_id == 2){
				$exists = $this->contract_service->addContractCheckPayment($this->contract_id, $this->payment_type_id, $this->amount, $this->check_bank, $this->check_bank, $this->receipt_remarks, auth()->user()->id);
			}
			else{
				$exists = $this->contract_service->addContractCashPayment($this->contract_id, $this->amount_to_be_paid, $this->payment_type_id, $this->amount, $this->receipt_remarks, auth()->user()->id);
			}

			if ($exists[0]->result_or_id == 1) {
				// Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Added new payment successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['contract_id', 'contract_id']);
			$this->reset(['contract_no_disabled', 'contract_no_disabled']);
			$this->reset(['client_name', 'client_name']);
			$this->reset(['contract_category_label', 'contract_category_label']);
			$this->reset(['contract_type_label', 'contract_type_label']);
			$this->reset(['contract_category_type_detail_label', 'contract_category_type_detail_label']);
			$this->reset(['account_amount', 'account_amount']);
			$this->reset(['amount_to_be_paid', 'amount_to_be_paid']);
			$this->reset(['payment_type_id', 'payment_type_id']);
			$this->reset(['amount', 'amount']);
			$this->reset(['check_bank', 'check_bank']);
			$this->reset(['check_no', 'check_no']);
			$this->reset(['receipt_remarks', 'receipt_remarks']);

			// Close the modal
			$this->showDrawer2 = false;

			$this->contract_lst();

			$this->redirect('/or-by-id/' . $exists[0]->result_or_id);

			
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

	public function openUpdateContractStatusModal(int $contract_id, int $statuscode){
    try{
      $this->updateContractStatusModal = true;
      $this->contract_id = $contract_id;
      $this->statuscode = $statuscode;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_contract_status($contract_id, $statuscode){
    try{
      $result = $this->contract_service->updateContractStatusById($contract_id, $statuscode, auth()->user()->id);
      
      // // Toast
      if ($result[0]->result_id > 1) {
        // Optional: Show error to user
        $this->addMessage = 'Updated contract status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }
			else if ($result[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Action Failed! Could not update status. Contract with payment cannot be deactivated or removed from the system.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
			else if ($result[0]->result_id == 0){
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }

      $this->updateContractStatusModal = false;	
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

	public function render(){
		return view('livewire.pages.contract.contract');
	}

}
