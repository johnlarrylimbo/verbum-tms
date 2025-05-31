<?php

namespace App\Livewire\Pages\Congregation;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\CongregationService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class Congregation extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $congregation_service;

  #public variables
  public $search;
  public $congregation_id;
  public $statuscode;

  public $abbreviation;
  public $description;

  public $edit_abbreviation;
  public $edit_description;

  #modals
  public bool $addCongregationModal = false;
  public bool $editCongregationModal = false;
  public bool $updateCongregationStatusModal = false;
  public $showAddSuccessMessage = false;
  public $showAddErrorMessage = false;
  public $showSuccessMessage = false;
  public $showErrorMessage = false;

	public function boot(
		CongregationService $congregation_service,
	)
	{
		$this->congregation_service = $congregation_service;
	}

  public function mount(){
		// Initialize form fields
		$this->abbreviation = '';
    $this->description = '';
	}

  public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function loadRecords
	public function congregation_lst(){
    if(!$this->search){
      $congregation_lst = $this->congregation_service->loadCongregationLst()->paginate(15);
		  return $congregation_lst;
    }else{
      $congregation_lst = $this->congregation_service->loadCongregationLstByKeyword($this->search)->paginate(15);
		  return $congregation_lst;
    }
	}

  // public function save barangay record changes
  public function save(){
		// Validation and saving logic

		$this->validate([
      'abbreviation' => 'required|string|max:45',
      'description' => 'required|string|max:2048'
		]);

    $exists = $this->congregation_service->addCongregation($this->abbreviation, $this->description, auth()->user()->id);

		if ($exists[0]->result_id == 0) {
			// $this->error('Failed to update record. Record does not exists.');
      $this->showAddErrorMessage = true;
		}
		else{
      // $this->success('Record updated successfully!');
      $this->showAddSuccessMessage = true;
		}

		// Optionally reset form fields after save
		$this->reset(['abbreviation', 'abbreviation']);
    $this->reset(['description', 'description']);

		// Close the modal
		$this->addCongregationModal = false;

		$this->congregation_lst();
	}

  // // public function get barangay by id
	// public function openEditBarangayModal(int $barangay_id){
  //   $this->resetValidation();  // clears validation errors
	// 	$this->editBarangayModal = true;
	// 	$this->barangay_id = $barangay_id;

  //   $result = $this->barangay_service->getBarangayById($this->barangay_id);

	// 	foreach($result as $result){
	// 		$this->edit_province_id = $result->province_id;
  //     $this->edit_city_municipality_id = $result->city_municipality_id;
  //     $this->edit_label = $result->label;
	// 	}
	// }

  // // public function save barangay record changes
  // public function save_barangay_record_changes(){
	// 	// Validation and saving logic

	// 	$this->validate([
  //     'edit_province_id' => 'required|not_in:0',
  //     'edit_city_municipality_id' => 'required|not_in:0',
  //     'edit_label' => 'required|string|max:255'
	// 	]);

  //   $exists = $this->barangay_service->updateBarangayById($this->barangay_id, $this->edit_province_id, $this->edit_city_municipality_id, $this->edit_label);

	// 	if ($exists[0]->result_id == 0) {
	// 		// $this->error('Failed to update record. Record does not exists.');
  //     $this->showErrorMessage = true;
	// 	}
	// 	else{
  //     // $this->success('Record updated successfully!');
  //     $this->showSuccessMessage = true;
	// 	}

	// 	// Optionally reset form fields after save
	// 	$this->reset(['barangay_id', 'barangay_id']);
  //   $this->reset(['edit_province_id', 'edit_province_id']);
  //   $this->reset(['edit_city_municipality_id', 'edit_city_municipality_id']);
  //   $this->reset(['edit_label', 'edit_label']);

	// 	// Close the modal
	// 	$this->editBarangayModal = false;

	// 	$this->barangay_lst();
	// }

  // public function openUpdateBarangayStatusModal(int $barangay_id, int $statuscode){
	// 	$this->updateBarangayStatusModal = true;
	// 	$this->barangay_id = $barangay_id;
  //   $this->statuscode = $statuscode;
	// }

  // public function update_barangay_status($barangay_id, $statuscode){
  //   // $param = [  $clearance_area_id, 0 ];
  //   // $sp_query = "EXEC pr_clearance_area_by_id_del :clearance_area_id, :result_id;";
  //   // $result = DB::connection('iclearance_connection')->select($sp_query, $param);

  //   $result = $this->barangay_service->updateBarangayStatusById($barangay_id, $statuscode, auth()->user()->id);
		
	// 	// // Toast
  //   if ($result[0]->result_id > 0) {
  //     $this->showSuccessMessage = true;
  //   }else{
  //     $this->showErrorMessage = true;
  //   }

	// 	// $this->reset('clearance_area_id');
	// 	$this->updateBarangayStatusModal = false;	
	// }


	public function render(){
		return view('livewire.pages.congregation.congregation');
	}

}
