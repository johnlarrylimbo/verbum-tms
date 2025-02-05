<?php

namespace App\Livewire\Pages\ClearanceType;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Models\ClearanceType;
use App\Services\ClearanceTypeService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class WireClearanceType extends Component
{

	use WithPagination;
	use Toast;

	protected $clearance_type_service;

	public bool $addClearanceTypeModal = false;
	public bool $editClearanceTypeModal = false;
	public bool $deleteClearanceTypeModal = false;

	public $clearance_type_id;

  public $abbreviation;
  public $description;

  public $edit_abbreviation;
  public $edit_description;

	public $search;	

	public function boot(
		ClearanceTypeService $clearance_type_service,
	)
	{
		$this->clearance_type_service = $clearance_type_service;
	}

	// Load records from the database
	#[Computed]
	// public function loadRecords
	public function clearance_type(){
		if(!$this->search){
			$clearance_type = $this->clearance_type_service->loadClearanceType()->paginate(10);
			return $clearance_type;
		}
		else{
			$clearance_type = $this->clearance_type_service->searchClearanceTypeByKeyword($this->search)->paginate(10);
			return $clearance_type;
		}
	}

	public function mount(){
		// Initialize form fields
		$this->abbreviation = '';
    $this->description = '';
	}

  public function save(){
		// Validation and saving logic
		$this->validate([
			'abbreviation' => 'required|string|max:64',
      'description' => 'required|string|max:512'
		]);

		// Check for duplicates
    $param = [  $this->abbreviation, $this->description, 0 ];
    $sp_query = "EXEC pr_clearance_type_check_exists :abbreviation, :description, :result_id;";
    $exists = DB::connection('iclearance_connection')->select($sp_query, $param);

		if ($exists[0]->result_id == 1) {
			$this->error('Record already exists.');
		}
		else{
      $param = [  $this->abbreviation, $this->description, 0 ];
      $sp_query = "EXEC pr_clearance_type_ins :abbreviation, :description, :result_id;";
      $result = DB::connection('iclearance_connection')->select($sp_query, $param);
			
      // Toast
      if ($result[0]->result_id > 0) {
        $this->success('Record added successfully!');
      }else{
        $this->success('Failed to add new clearance type!');
      }
		}

		// Optionally reset form fields after save
    $this->reset(['abbreviation', 'abbreviation']);
    $this->reset(['description', 'description']);
		// Close the modal
		$this->addClearanceTypeModal  = false;

		$this->clearance_type();
	}

  // public function get records by id
	public function openEditClearanceTypeModal(int $clearance_type_id){
		$this->editClearanceTypeModal = true;
		$this->clearance_type_id = $clearance_type_id;

    $param = [  $clearance_type_id ];
    $sp_query = "EXEC pr_clearance_type_by_id_sel :clearance_type_id;";
    $result = DB::connection('iclearance_connection')->select($sp_query, $param);

		foreach($result as $result){
      $this->edit_abbreviation = $result->abbreviation;
      $this->edit_description = $result->label;
		}
	}

  public function save_edit(){
		// Validation and saving logic
		$this->validate([
			'edit_abbreviation' => 'required|string|max:64',
      'edit_description' => 'required|string|max:512'
		]);

		// Check for duplicates
    $param = [  $this->clearance_type_id, 0 ];
    $sp_query = "EXEC pr_clearance_type_check_exists_by_id :clearance_type_id, :result_id;";
    $exists = DB::connection('iclearance_connection')->select($sp_query, $param);

		if ($exists[0]->result_id == 0) {
			// Toast
			$this->error('Record does not exists.');
		}
		else{
      $param = [  $this->clearance_type_id, $this->edit_abbreviation, $this->edit_description, 0 ];
      $sp_query = "EXEC pr_clearance_type_by_id_upd :clearance_type_id, :abbreviation, :description, :result_id;";
      $result = DB::connection('iclearance_connection')->select($sp_query, $param);
			
      // Toast
      if ($result[0]->result_id > 0) {
        $this->success('Record updated successfully!');
      }else{
        $this->success('Failed to update clearance area. Please try again later.');
      }
		}

		// Optionally reset form fields after save
		$this->reset(['clearance_type_id', 'clearance_type_id']);
    $this->reset(['edit_abbreviation', 'edit_abbreviation']);
    $this->reset(['edit_description', 'edit_description']);

		// Close the modal
		$this->editClearanceTypeModal  = false;

		$this->clearance_type();
	}

  public function openDeleteClearanceTypeModal(int $clearance_type_id){
		$this->deleteClearanceTypeModal = true;
		$this->clearance_type_id = $clearance_type_id;
	}

	public function delete($clearance_type_id){
    $param = [  $clearance_type_id, 0 ];
    $sp_query = "EXEC pr_clearance_type_by_id_del :clearance_type_id, :result_id;";
    $result = DB::connection('iclearance_connection')->select($sp_query, $param);
		
		// Toast
    if ($result[0]->result_id > 0) {
      $this->success('Record deleted successfully!');
    }else{
      $this->error('Failed to remove clearance type. Clearance type might be used by other records or please try again later.');
    }

		$this->reset('clearance_type_id');
		$this->deleteClearanceTypeModal = false;	
	}


	public function render(){
		return view('livewire.pages.clearance-type.clearance-type');
	}

}
