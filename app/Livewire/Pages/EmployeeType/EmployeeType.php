<?php

namespace App\Livewire\Pages\EmployeeType;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\EmployeeTypeService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class EmployeeType extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $employee_type_service;

  #public variables
  public $search;
  public $employee_type_id;
  public $statuscode;

  public $label;

  public $edit_label;

  #modals
  public bool $addEmployeeTypeModal = false;
  public bool $editEmployeeTypeModal = false;
  public bool $updateEmployeeTypeStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		EmployeeTypeService $employee_type_service,
	)
	{
		$this->employee_type_service = $employee_type_service;
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
	// public function load employee type records
	public function employee_type_lst(){
		try{
			if(!$this->search){
				$employee_type_lst = $this->employee_type_service->loadEmployeeTypeLst()->paginate(15);
				return $employee_type_lst;
			}else{
				$employee_type_lst = $this->employee_type_service->loadEmployeeTypeLstByKeyword($this->search)->paginate(15);
				return $employee_type_lst;
			}
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save employee type record changes
  public function save_employee_type(){
		try{
			// Validation and saving logic
			$this->validate([
				'label' => 'required|string|max:255'
			]);

			$exists = $this->employee_type_service->addEmployeeType($this->label, auth()->user()->id);

			if ($exists[0]->result_id == 1) {
				// Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Added new employee type successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['label', 'label']);

			// Close the modal
			$this->addEmployeeTypeModal = false;

			$this->employee_type_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get employee type by id
	public function openEditEmployeeTypeModal(int $employee_type_id){
		try{
			$this->resetValidation();  // clears validation errors
			$this->editEmployeeTypeModal = true;
			$this->employee_type_id = $employee_type_id;

			$result = $this->employee_type_service->getEmployeeTypeById($this->employee_type_id);

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

  // public function save employee type record changes
  public function save_employee_type_record_changes(){
		try{
			// Validation and saving logic
			$this->validate([
				'edit_label' => 'required|string|max:255'
			]);

			$exists = $this->employee_type_service->updateEmployeeTypeById($this->employee_type_id, $this->edit_label, auth()->user()->id);

			if ($exists[0]->result_id == 0) {
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}
			else{
				// Optional: Show error to user
        $this->addMessage = 'Updated employee type successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}

			// Optionally reset form fields after save
			$this->reset(['employee_type_id', 'employee_type_id']);
			$this->reset(['edit_label', 'edit_label']);

			// Close the modal
			$this->editEmployeeTypeModal = false;

			$this->employee_type_lst();
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateEmployeeTypeStatusModal(int $employee_type_id, int $statuscode){
		try{
			$this->updateEmployeeTypeStatusModal = true;
			$this->employee_type_id = $employee_type_id;
			$this->statuscode = $statuscode;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_employee_type_status($employee_type_id, $statuscode){
		try{
			$result = $this->employee_type_service->updateEmployeeTypeStatusById($employee_type_id, $statuscode, auth()->user()->id);
			
			// // Toast
			if ($result[0]->result_id > 0) {
				// Optional: Show error to user
        $this->addMessage = 'Updated employee type status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
			}else{
				// Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
			}

			$this->updateEmployeeTypeStatusModal = false;
		} catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }	
	}


	public function render(){
		return view('livewire.pages.employee-type.employee-type');
	}

}
