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

  public $showAddSuccessMessage = false;
  public $showAddErrorMessage = false;
  public $showSuccessMessage = false;
  public $showErrorMessage = false;

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
    if(!$this->search){
      $client_category_lst = $this->client_category_service->loadClientCategoryLst()->paginate(15);
		  return $client_category_lst;
    }else{
      $client_category_lst = $this->client_category_service->loadClientCategoryLstByKeyword($this->search)->paginate(15);
		  return $client_category_lst;
    }
	}

  // public function save client category record changes
  public function save(){
		// Validation and saving logic

		$this->validate([
      'label' => 'required|string|max:256'
		]);

    $exists = $this->client_category_service->addClientCategory($this->label, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
      $this->showAddErrorMessage = true;
		}
		else{
      $this->showAddSuccessMessage = true;
		}

		// Optionally reset form fields after save
    $this->reset(['label', 'label']);

		// Close the modal
		$this->addClientCategoryModal = false;

		$this->client_category_lst();
	}

  // public function get client category by id
	public function openEditClientCategoryModal(int $client_category_id){
    $this->resetValidation();  // clears validation errors
		$this->editClientCategoryModal = true;
		$this->client_category_id = $client_category_id;

    $result = $this->client_category_service->getClientCategoryById($this->client_category_id);

		foreach($result as $result){
      $this->edit_label = $result->label;
		}
	}

  // public function save client category record changes
  public function save_client_category_record_changes(){
		// Validation and saving logic
    $this->validate([
      'edit_label' => 'required|string|max:256'
		]);

    $exists = $this->client_category_service->updateClientCategoryById($this->client_category_id, $this->edit_label, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
      $this->showErrorMessage = true;
		}
		else{
      $this->showSuccessMessage = true;
		}

		// Optionally reset form fields after save
    $this->reset(['client_category_id', 'client_category_id']);
    $this->reset(['edit_label', 'edit_label']);

		// Close the modal
		$this->editClientCategoryModal = false;

		$this->client_category_lst();
	}

  public function openUpdateClientCategoryStatusModal(int $client_category_id, int $statuscode){
		$this->updateClientCategoryStatusModal = true;
		$this->client_category_id = $client_category_id;
    $this->statuscode = $statuscode;
	}

  public function update_client_category_status($client_category_id, $statuscode){

    $result = $this->client_category_service->updateClientCategoryStatusById($client_category_id, $statuscode, auth()->user()->id);
		
		// // Toast
    if ($result[0]->result_id > 0) {
      $this->showSuccessMessage = true;
    }else{
      $this->showErrorMessage = true;
    }

		$this->updateClientCategoryStatusModal = false;	
	}


	public function render(){
		return view('livewire.pages.client-category.client-category');
	}

}
