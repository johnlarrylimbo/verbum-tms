<?php

namespace App\Livewire\Pages\ClientCategory;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\ClientCategoryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class ClientCategory extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $client_category_service;

  #public variables
  public $search;
  public $client_category_id;
  public $statuscode;

  public $label;

  public $edit_label;

  #modals
  public bool $addClientCategoryModal = false;
  public bool $editClientCategoryModal = false;
  public bool $updateClientCategoryStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		ClientCategoryService $client_category_service,
	)
	{
		$this->client_category_service = $client_category_service;
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
	// public function load client category records
	public function client_category_lst(){
		try{
			if(!$this->search){
				$client_category_lst = $this->client_category_service->loadClientCategoryLst()->paginate(15);
				return $client_category_lst;
			}else{
				$client_category_lst = $this->client_category_service->loadClientCategoryLstByKeyword($this->search)->paginate(15);
				return $client_category_lst;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save client category record changes
  public function save_client_category(){
		try{
			// Validation and saving logic
			$this->validate([
				'label' => 'required|string|max:256'
			]);

			$exists = $this->client_category_service->addClientCategory($this->label, auth()->user()->id);

			if ($exists[0]->result_id == 1) {
				// Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Added new client category successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['label', 'label']);

			// Close the modal
			$this->addClientCategoryModal = false;

			$this->client_category_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get client category by id
	public function openEditClientCategoryModal(int $client_category_id){
		try{
			$this->resetValidation();  // clears validation errors
			$this->editClientCategoryModal = true;
			$this->client_category_id = $client_category_id;

			$result = $this->client_category_service->getClientCategoryById($this->client_category_id);

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

  // public function save client category record changes
  public function save_client_category_record_changes(){
		try{
			// Validation and saving logic
			$this->validate([
				'edit_label' => 'required|string|max:256'
			]);

			$exists = $this->client_category_service->updateClientCategoryById($this->client_category_id, $this->edit_label, auth()->user()->id);

			if ($exists[0]->result_id == 0) {
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Updated client category successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['client_category_id', 'client_category_id']);
			$this->reset(['edit_label', 'edit_label']);

			// Close the modal
			$this->editClientCategoryModal = false;

			$this->client_category_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateClientCategoryStatusModal(int $client_category_id, int $statuscode){
		try{
			$this->updateClientCategoryStatusModal = true;
			$this->client_category_id = $client_category_id;
			$this->statuscode = $statuscode;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_client_category_status($client_category_id, $statuscode){
		try{
			$result = $this->client_category_service->updateClientCategoryStatusById($client_category_id, $statuscode, auth()->user()->id);
			
			// // Toast
			if ($result[0]->result_id > 0) {
				// Optional: Show error to user
        $this->addMessage = 'Updated client category status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}else{
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}

			$this->updateClientCategoryStatusModal = false;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }	
	}


	public function render(){
		return view('livewire.pages.client-category.client-category');
	}

}
