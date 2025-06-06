<?php

namespace App\Livewire\Pages\BasicEcclesialCommunity;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\BasicEcclesialCommunityService;
use App\Services\SelectOptionLibraryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class BasicEcclesialCommunity extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $bec_service;
  protected $select_option_service;

  #public variables
  public $search;
  public $bec_id;
  public $statuscode;

  public $parish_id;
  public $name;
  public $address;

  public $edit_parish_id;
  public $edit_name;
  public $edit_address;

  #modals
  public bool $addBasicEcclesialCommunityModal = false;
  public bool $editBasicEcclesialCommunityModal = false;
  public bool $updateBasicEcclesialCommunityStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		BasicEcclesialCommunityService $bec_service,
    SelectOptionLibraryService $select_option_service,
	)
	{
		$this->bec_service = $bec_service;
    $this->select_option_service = $select_option_service;
	}

  public function mount(){
		// Initialize form fields
    $this->parish_id = 0;
    $this->name = '';
    $this->address = '';
	}

  public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load bec records
	public function bec_lst(){
    try{
      if(!$this->search){
        $bec_lst = $this->bec_service->loadBasicEcclesialCommunityLst()->paginate(15);
        return $bec_lst;
      }else{
        $bec_lst = $this->bec_service->loadBasicEcclesialCommunityLstByKeyword($this->search)->paginate(15);
        return $bec_lst;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  #[Computed]
	// public function load parish options
	public function load_parish_options(){
		return $this->select_option_service->loadParishesOptions();
	}

  // public function save bec record changes
  public function save_bec(){
    try{
      // Validation and saving logic
      $this->validate([
        'parish_id' => 'required|not_in:0',
        'name' => 'required|string|max:2048',
        'address' => 'required|string|max:2048'
      ]);

      $exists = $this->bec_service->addBasicEcclesialCommunity($this->parish_id, $this->name, $this->address, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new basic ecclesial community successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['parish_id', 'parish_id']);
      $this->reset(['name', 'name']);
      $this->reset(['address', 'address']);

      // Close the modal
      $this->addBasicEcclesialCommunityModal = false;

      $this->bec_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get bec by id
	public function openEditBasicEcclesialCommunityModal(int $bec_id){
    try{
      $this->resetValidation();  // clears validation errors
      $this->editBasicEcclesialCommunityModal = true;
      $this->bec_id = $bec_id;

      $result = $this->bec_service->getBasicEcclesialCommunityById($this->bec_id);

      foreach($result as $result){
        $this->edit_parish_id = $result->parish_id;
        $this->edit_name = $result->name;
        $this->edit_address = $result->address;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save bec record changes
  public function save_bec_record_changes(){
    try{
      // Validation and saving logic
      $this->validate([
        'edit_parish_id' => 'required|not_in:0',
        'edit_name' => 'required|string|max:2048',
        'edit_address' => 'required|string|max:2048'
      ]);

      $exists = $this->bec_service->updateBasicEcclesialCommunityById($this->bec_id, $this->edit_parish_id, $this->edit_name, $this->edit_address, auth()->user()->id);

      if ($exists[0]->result_id == 0) {
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Updated basic ecclesial community successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['bec_id', 'bec_id']);
      $this->reset(['edit_parish_id', 'edit_parish_id']);
      $this->reset(['edit_name', 'edit_name']);
      $this->reset(['edit_address', 'edit_address']);

      // Close the modal
      $this->editBasicEcclesialCommunityModal = false;

      $this->bec_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateBasicEcclesialCommunityStatusModal(int $bec_id, int $statuscode){
    try{
      $this->updateBasicEcclesialCommunityStatusModal = true;
      $this->bec_id = $bec_id;
      $this->statuscode = $statuscode;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_bec_status($bec_id, $statuscode){
    try{
      $result = $this->bec_service->updateBasicEcclesialCommunityStatusById($bec_id, $statuscode, auth()->user()->id);
      
      // // Toast
      if ($result[0]->result_id > 0) {
        // Optional: Show error to user
        $this->addMessage = 'Updated basic ecclesial community status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }else{
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }

      $this->updateBasicEcclesialCommunityStatusModal = false;	
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}


	public function render(){
		return view('livewire.pages.basic-ecclesial-community.basic-ecclesial-community');
	}

}
