<?php

namespace App\Livewire\Pages\IslandGroup;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\IslandGroupService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class IslandGroup extends Component
{

	use WithPagination;
	use Toast;

	#protected variables
	protected $island_group_service;

  #public variables
  public $search;
  public $island_group_id;
  public $statuscode;

  public $abbreviation;
  public $label;

  public $edit_abbreviation;
  public $edit_label;

  #modals
  public bool $addIslandGroupModal = false;
  public bool $editIslandGroupModal = false;
  public bool $updateIslandGroupStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		IslandGroupService $island_group_service,
	)
	{
		$this->island_group_service = $island_group_service;
	}

	public function mount(){
		// Initialize form fields
    $this->island_group_id = 0;
    $this->region_id = 0;
    $this->label = '';
	}

	public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load island group records
	public function island_group_lst(){
		try{
			if(!$this->search){
				$island_group_lst = $this->island_group_service->loadIslandGroupLst()->paginate(15);
				return $island_group_lst;
			}else{
				$island_group_lst = $this->island_group_service->loadIslandGroupLstByKeyword($this->search)->paginate(15);
				return $island_group_lst;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save island group record changes
  public function save_island_group(){
		try{
			// Validation and saving logic
			$this->validate([
				'abbreviation' => 'required|string|max:64',
				'label' => 'required|string|max:255',
			]);

			$exists = $this->island_group_service->addIslandGroup($this->abbreviation, $this->label, auth()->user()->id);

			if ($exists[0]->result_id == 1) {
				// Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Added new island group successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['abbreviation', 'abbreviation']);
			$this->reset(['label', 'label']);

			// Close the modal
			$this->addIslandGroupModal = false;

			$this->island_group_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get island group by id
	public function openEditIslandGroupModal(int $island_group_id){
		try{
			$this->resetValidation();  // clears validation errors
			$this->editIslandGroupModal = true;
			$this->island_group_id = $island_group_id;

			$result = $this->island_group_service->getIslandGroupById($this->island_group_id);

			foreach($result as $result){
				$this->edit_abbreviation = $result->abbreviation;
				$this->edit_label = $result->label;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save island group record changes
  public function save_island_group_record_changes(){
		try{
			// Validation and saving logic
			$this->validate([
				'edit_abbreviation' => 'required|string|max:64',
				'edit_label' => 'required|string|max:255'
			]);

			$exists = $this->island_group_service->updateIslandGroupById($this->island_group_id, $this->edit_abbreviation, $this->edit_label, auth()->user()->id);

			if ($exists[0]->result_id == 0) {
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Updated island group successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['island_group_id', 'island_group_id']);
			$this->reset(['edit_abbreviation', 'edit_abbreviation']);
			$this->reset(['edit_label', 'edit_label']);

			// Close the modal
			$this->editIslandGroupModal = false;

			$this->island_group_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateIslandGroupStatusModal(int $island_group_id, int $statuscode){
		try{
			$this->updateIslandGroupStatusModal = true;
			$this->island_group_id = $island_group_id;
			$this->statuscode = $statuscode;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_island_group_status($island_group_id, $statuscode){
		try{
			$result = $this->island_group_service->updateIslandGroupStatusById($island_group_id, $statuscode, auth()->user()->id);
			
			// // Toast
			if ($result[0]->result_id > 0) {
				// Optional: Show error to user
        $this->addMessage = 'Updated island group status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}else{
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}

			$this->updateIslandGroupStatusModal = false;	
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}


	public function render(){
		return view('livewire.pages.island-group.island-group');
	}

}
