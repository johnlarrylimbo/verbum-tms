<?php

namespace App\Livewire\Pages\AccessPermission;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\AccessPermissionService;
use App\Services\SelectOptionLibraryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class WireAccessPermission extends Component
{

	use WithPagination;
	use Toast;

	protected $access_permission_service;
  protected $select_option_library_service;

	public bool $addAccessPermissionRequestModal = false;
	public bool $editAccessPermissionRequestModal = false;
	public bool $deleteAccessPermissionRequestModal = false;

	public $access_permission_request_id;

	public $clearance_area_id;

  public $edit_clearance_area_id;	

	public function boot(
		AccessPermissionService $access_permission_service,
    SelectOptionLibraryService $select_option_library_service,
	)
	{
		$this->access_permission_service = $access_permission_service;
    $this->select_option_library_service = $select_option_library_service;
	}

	// Load records from the database
	#[Computed]
	// public function loadRecords
	public function access_permission_request(){
		$access_permission_request = $this->access_permission_service->loadAccessPermissionRequest(auth()->user()->user_account_id);
		return $access_permission_request->paginate(10);
	}

  #[Computed]
	// public function loadHealthClaimCategoryOptions()
	public function clearance_area_item_options(){
		return $this->select_option_library_service->loadClearanceAreaItemSelectOptions();
	}

	public function mount(){
		// Initialize form fields
    $this->clearance_area_id = 0;
	}

  public function save(){
		// Validation and saving logic
		$this->validate([
      'clearance_area_id' => 'required|not_in:0'
		]);

		// Check for duplicates
    $param = [  $this->clearance_area_id, auth()->user()->user_account_id, 0 ];
    $sp_query = "EXEC pr_access_permission_request_check_exists :clearance_area_id, :account_id, :result_id;";
    $exists = DB::connection('iclearance_connection')->select($sp_query, $param);

		if ($exists[0]->result_id == 1) {
			$this->error('Clearance area permission record granted and already exists.');
		}
		else{
      $param = [  $this->clearance_area_id, auth()->user()->user_account_id, 0 ];
      $sp_query = "EXEC pr_access_permission_request_ins :clearance_area_id, :account_id, :result_id;";
      $result = DB::connection('iclearance_connection')->select($sp_query, $param);
			
      // Toast
      if ($result[0]->result_id > 0) {
        $this->success('Record added successfully!');
      }else{
        $this->success('Failed to add new role!');
      }
		}

		// Optionally reset form fields after save
		$this->reset(['clearance_area_id', 'clearance_area_id']);

		// Close the modal
		$this->addAccessPermissionRequestModal  = false;

		$this->access_permission_request();
	}

  // public function get records by id
	public function openEditAccessPermissionRequestModal(int $access_permission_request_id){
		$this->editAccessPermissionRequestModal = true;
		$this->access_permission_request_id = $access_permission_request_id;

    $param = [  $access_permission_request_id ];
    $sp_query = "EXEC pr_access_permission_request_by_id_sel :access_permission_request_id;";
    $result = DB::connection('iclearance_connection')->select($sp_query, $param);

		foreach($result as $result){
			$this->edit_clearance_area_id = $result->clearance_area_id;
		}
	}

  public function save_edit(){
		// Validation and saving logic
		$this->validate([
			'edit_clearance_area_id' => 'required|not_in:0'
		]);

		// Check for duplicates
    $param = [  $this->access_permission_request_id, 0 ];
    $sp_query = "EXEC pr_access_permission_request_check_exists_by_id :access_permission_request_id, :result_id;";
    $exists = DB::connection('iclearance_connection')->select($sp_query, $param);

		if ($exists[0]->result_id == 0) {
			// Toast
			$this->error('Access permission request does not exists.');
		}
		else{
      $param = [  $this->access_permission_request_id, $this->edit_clearance_area_id, 0 ];
      $sp_query = "EXEC pr_access_permission_request_by_id_upd :access_permission_request_id, :clearance_area_id, :result_id;";
      $result = DB::connection('iclearance_connection')->select($sp_query, $param);
			
      // Toast
      if ($result[0]->result_id > 0) {
        $this->success('Access permission request updated successfully!');
      }else{
        $this->success('Failed to update access permission request. Please try again later.');
      }
		}

		// Optionally reset form fields after save
		$this->reset(['access_permission_request_id', 'access_permission_request_id']);
    $this->reset(['edit_clearance_area_id', 'edit_clearance_area_id']);

		// Close the modal
		$this->editAccessPermissionRequestModal  = false;

		$this->access_permission_request();
	}

  public function openDeleteAccessPermissionRequestModal(int $access_permission_request_id){
		$this->deleteAccessPermissionRequestModal = true;
		$this->access_permission_request_id = $access_permission_request_id;
	}

  public function delete($access_permission_request_id){
    $param = [  $access_permission_request_id, 0 ];
    $sp_query = "EXEC pr_access_permission_request_by_id_del :access_permission_request_id, :result_id;";
    $result = DB::connection('iclearance_connection')->select($sp_query, $param);
		
		// Toast
    if ($result[0]->result_id > 0) {
      $this->success('Access permission request deleted successfully!');
    }else{
      $this->error('Failed to remove access permission request. Request might be used by other records or please try again later.');
    }

		$this->reset('access_permission_request_id');
		$this->deleteAccessPermissionRequestModal = false;	
	}

	public function render(){
		return view('livewire.pages.access-permission.access-permission');
	}

}
