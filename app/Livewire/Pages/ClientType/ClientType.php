<?php

namespace App\Livewire\Pages\ClientType;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\ClientTypeService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class ClientType extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $client_type_service;

  #public variables
  public $search;
  public $client_type_id;
  public $statuscode;

  public $label;

  public $edit_label;

  #modals
  public bool $addClientTypeModal = false;
  public bool $editClientTypeModal = false;
  public bool $updateClientTypeStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		ClientTypeService $client_type_service,
	)
	{
		$this->client_type_service = $client_type_service;
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
	// public function load client type records
	public function client_type_lst(){
		try{
			if(!$this->search){
				$client_type_lst = $this->client_type_service->loadClientTypeLst()->paginate(15);
				return $client_type_lst;
			}else{
				$client_type_lst = $this->client_type_service->loadClientTypeLstByKeyword($this->search)->paginate(15);
				return $client_type_lst;
			}
		} catch(e){
     // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save client type record changes
  public function save_client_type(){
		try{
			// Validation and saving logic
			$this->validate([
				'label' => 'required|string|max:255'
			]);

			$exists = $this->client_type_service->addClientType($this->label, auth()->user()->id);

			if ($exists[0]->result_id == 1) {
				// Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Added new client type successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['label', 'label']);

			// Close the modal
			$this->addClientTypeModal = false;

			$this->client_type_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get client type by id
	public function openEditClientTypeModal(int $client_type_id){
		try{
			$this->resetValidation();  // clears validation errors
			$this->editClientTypeModal = true;
			$this->client_type_id = $client_type_id;

			$result = $this->client_type_service->getClientTypeById($this->client_type_id);

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

  // public function save client type record changes
  public function save_client_type_record_changes(){
		try{
			// Validation and saving logic
			$this->validate([
				'edit_label' => 'required|string|max:255'
			]);

			$exists = $this->client_type_service->updateClientTypeById($this->client_type_id, $this->edit_label, auth()->user()->id);

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
			$this->reset(['client_type_id', 'client_type_id']);
			$this->reset(['edit_label', 'edit_label']);

			// Close the modal
			$this->editClientTypeModal = false;

			$this->client_type_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateClientTypeStatusModal(int $client_type_id, int $statuscode){
		try{
			$this->updateClientTypeStatusModal = true;
			$this->client_type_id = $client_type_id;
			$this->statuscode = $statuscode;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_client_type_status($client_type_id, $statuscode){
		try{
			$result = $this->client_type_service->updateClientTypeStatusById($client_type_id, $statuscode, auth()->user()->id);
			
			// // Toast
			if ($result[0]->result_id > 0) {
				// Optional: Show error to user
        $this->addMessage = 'Updated client type status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}else{
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}

			$this->updateClientTypeStatusModal = false;	
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}


	public function render(){
		return view('livewire.pages.client-type.client-type');
	}

}
