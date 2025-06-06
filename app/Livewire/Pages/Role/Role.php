<?php

namespace App\Livewire\Pages\Role;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\RoleService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class Role extends Component
{

	use WithPagination;
	use Toast;

	#protected variables
	protected $role_service;

  #public variables
  public $search;
  public $role_id;
  public $statuscode;

  public $abbreviation;
	public $label;

  public $edit_abbreviation;
	public $edit_label;

  #modals
  public bool $addRoleModal = false;
  public bool $editRoleModal = false;
  public bool $updateRoleStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		RoleService $role_service,
	)
	{
		$this->role_service = $role_service;
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
	// public function load role records
	public function role_lst(){
		try{
			if(!$this->search){
				$role_lst = $this->role_service->loadRoleLst()->paginate(15);
				return $role_lst;
			}else{
				$role_lst = $this->role_service->loadRoleLstByKeyword($this->search)->paginate(15);
				return $role_lst;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save role record changes
  public function save_role(){
		try{
			// Validation and saving logic
			$this->validate([
				'abbreviation' => 'required|string|max:255',
				'label' => 'required|string|max:255'
			]);

			$exists = $this->role_service->addRole($this->abbreviation, $this->label, auth()->user()->id);

			if ($exists[0]->result_id == 1) {
				// Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Added new role successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['abbreviation', 'abbreviation']);
			$this->reset(['label', 'label']);

			// Close the modal
			$this->addRoleModal = false;

			$this->role_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get role by id
	public function openEditRoleModal(int $role_id){
		try{
			$this->resetValidation();  // clears validation errors
			$this->editRoleModal = true;
			$this->role_id = $role_id;

			$result = $this->role_service->getRoleById($this->role_id);

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

  // public function save role record changes
  public function save_role_record_changes(){
		try{
			// Validation and saving logic
			$this->validate([
				'edit_abbreviation' => 'required|string|max:255',
				'edit_label' => 'required|string|max:255'
			]);

			$exists = $this->role_service->updateRoleById($this->role_id, $this->edit_abbreviation, $this->edit_label, auth()->user()->id);

			if ($exists[0]->result_id == 0) {
				 // Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Updated role successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['role_id', 'role_id']);
			$this->reset(['edit_abbreviation', 'edit_abbreviation']);
			$this->reset(['edit_label', 'edit_label']);

			// Close the modal
			$this->editRoleModal = false;

			$this->role_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateRoleStatusModal(int $role_id, int $statuscode){
		try{
			$this->updateRoleStatusModal = true;
			$this->role_id = $role_id;
			$this->statuscode = $statuscode;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_role_status($role_id, $statuscode){
		try{
			$result = $this->role_service->updateRoleStatusById($role_id, $statuscode, auth()->user()->id);
			
			// // Toast
			if ($result[0]->result_id > 0) {
				// Optional: Show error to user
        $this->addMessage = 'Updated role status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}else{
				 // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}

			$this->updateRoleStatusModal = false;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }	
	}


	public function render(){
		return view('livewire.pages.role.role');
	}

}
