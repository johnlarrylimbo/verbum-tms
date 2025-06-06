<?php

namespace App\Livewire\Pages\PaymentType;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\PaymentTypeService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class PaymentType extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $payment_type_service;

  #public variables
  public $search;
  public $payment_type_id;
  public $statuscode;

  public $label;

  public $edit_label;

  #modals
  public bool $addPaymentTypeModal = false;
  public bool $editPaymentTypeModal = false;
  public bool $updatePaymentTypeStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		PaymentTypeService $payment_type_service,
	)
	{
		$this->payment_type_service = $payment_type_service;
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
	// public function load payment type records
	public function payment_type_lst(){
		try{
			if(!$this->search){
				$payment_type_lst = $this->payment_type_service->loadPaymentTypeLst()->paginate(15);
				return $payment_type_lst;
			}else{
				$payment_type_lst = $this->payment_type_service->loadPaymentTypeLstByKeyword($this->search)->paginate(15);
				return $payment_type_lst;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save payment type record changes
  public function save_payment_type(){
		try{
			// Validation and saving logic
			$this->validate([
				'label' => 'required|string|max:256'
			]);

			$exists = $this->payment_type_service->addPaymentType($this->label, auth()->user()->id);

			if ($exists[0]->result_id == 1) {
				// Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Added new payment type successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['label', 'label']);

			// Close the modal
			$this->addPaymentTypeModal = false;

			$this->payment_type_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get payment type by id
	public function openEditPaymentTypeModal(int $payment_type_id){
		try{
			$this->resetValidation();  // clears validation errors
			$this->editPaymentTypeModal = true;
			$this->payment_type_id = $payment_type_id;

			$result = $this->payment_type_service->getPaymentTypeById($this->payment_type_id);

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

  // public function save bec record changes
  public function save_payment_type_record_changes(){
		try{
			// Validation and saving logic
			$this->validate([
				'edit_label' => 'required|string|max:256'
			]);

			$exists = $this->payment_type_service->updatePaymentTypeById($this->payment_type_id, $this->edit_label, auth()->user()->id);

			if ($exists[0]->result_id == 0) {
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Updated payment type successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['payment_type_id', 'payment_type_id']);
			$this->reset(['edit_label', 'edit_label']);

			// Close the modal
			$this->editPaymentTypeModal = false;

			$this->payment_type_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdatePaymentTypeStatusModal(int $payment_type_id, int $statuscode){
		try{
			$this->updatePaymentTypeStatusModal = true;
			$this->payment_type_id = $payment_type_id;
			$this->statuscode = $statuscode;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_payment_type_status($payment_type_id, $statuscode){
		try{
			$result = $this->payment_type_service->updatePaymentTypeStatusById($payment_type_id, $statuscode, auth()->user()->id);
			
			// Toast
			if ($result[0]->result_id > 0) {
				// Optional: Show error to user
        $this->addMessage = 'Updated payment type status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}else{
				 // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}

			$this->updatePaymentTypeStatusModal = false;	
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}


	public function render(){
		return view('livewire.pages.payment-type.payment-type');
	}

}
