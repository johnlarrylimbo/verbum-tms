<?php

namespace App\Livewire\Pages\Roles;

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
class WireRoles extends Component
{

	use WithPagination;
	use Toast;

	protected $role_service;

	public bool $addRoleModal = false;
	public bool $editRoleModal = false;
	public bool $deleteRoleModal = false;

	public $role_id;

	public $description;
	public $edit_description;

	public $search;	

	public function boot(
		RoleService $role_service,
	)
	{
		$this->role_service = $role_service;
	}

	// Load records from the database
	#[Computed]
	// public function loadRecords
	public function roles(){
		if(!$this->search){
			$roles = $this->role_service->loadRoles()->paginate(10);
			return $roles;
		}
		else{
			$roles = $this->role_service->searchRolesByKeyword($this->search)->paginate(10);
			return $roles;
		}
	}

	public function mount(){
		// Initialize form fields
		$this->description = '';
	}

  public function save(){
		// Validation and saving logic
		$this->validate([
			'description' => 'required|string|max:255'
		]);

		// Check for duplicates
    $param = [  $this->description, 0 ];
    $sp_query = "EXEC pr_role_check_exists :label, :result_id;";
    $exists = DB::connection('iclearance_connection')->select($sp_query, $param);

		if ($exists[0]->result_id == 1) {
			$this->error('Record already exists.');
		}
		else{
      $param = [  $this->description, 0 ];
      $sp_query = "EXEC pr_role_ins :label, :result_id;";
      $result = DB::connection('iclearance_connection')->select($sp_query, $param);
			
      // Toast
      if ($result[0]->result_id > 0) {
        $this->success('Record added successfully!');
      }else{
        $this->success('Failed to add new role!');
      }
		}

		// Optionally reset form fields after save
		$this->reset(['description', 'description']);
		// Close the modal
		$this->addRoleModal  = false;

		$this->roles();
	}

  // public function get records by id
	public function openEditRoleModal(int $role_id){
		$this->editRoleModal = true;
		$this->role_id = $role_id;

    $param = [  $role_id ];
    $sp_query = "EXEC pr_role_by_id_sel :role_id;";
    $result = DB::connection('iclearance_connection')->select($sp_query, $param);

		foreach($result as $result){
			$this->edit_description = $result->label;
		}
	}

  public function save_edit(){
		// Validation and saving logic
		$this->validate([
			'edit_description' => 'required|string|max:256'
		]);

		// Check for duplicates
    $param = [  $this->role_id, 0 ];
    $sp_query = "EXEC pr_role_check_exists_by_id :role_id, :result_id;";
    $exists = DB::connection('iclearance_connection')->select($sp_query, $param);

		if ($exists[0]->result_id == 0) {
			// Toast
			$this->error('Record does not exists.');
		}
		else{
      $param = [  $this->role_id, $this->edit_description, 0 ];
      $sp_query = "EXEC pr_role_by_id_upd :role_id, :description, :result_id;";
      $result = DB::connection('iclearance_connection')->select($sp_query, $param);
			
      // Toast
      if ($result[0]->result_id > 0) {
        $this->success('Record updated successfully!');
      }else{
        $this->success('Failed to updated role record. Please try again later.');
      }
		}

		// Optionally reset form fields after save
		$this->reset(['role_id', 'role_id']);
    $this->reset(['edit_description', 'edit_description']);

		// Close the modal
		$this->editRoleModal  = false;

		$this->roles();
	}

  public function openDeleteRoleModal(int $role_id){
		$this->deleteRoleModal = true;
		$this->role_id = $role_id;
	}

	public function delete($role_id){
    $param = [  $role_id, 0 ];
    $sp_query = "EXEC pr_role_by_id_del :role_id, :result_id;";
    $result = DB::connection('iclearance_connection')->select($sp_query, $param);
		
		// Toast
    if ($result[0]->result_id > 0) {
      $this->success('Record deleted successfully!');
    }else{
      $this->error('Failed to remove role. Please try again later.');
    }

		$this->reset('role_id');
		$this->deleteRoleModal = false;	
	}


	public function render(){
		return view('livewire.pages.roles.roles');
	}

}
