<?php

namespace App\Livewire\Pages\Barangay;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\BarangayService;
use App\Services\SelectOptionLibraryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class Barangay extends Component
{

	use WithPagination;
	use Toast;

  #protected variables
	protected $barangay_service;
  protected $select_option_service;

  #public variables
  public $search;
  public $barangay_id;
  public $statuscode;

  public $province_id;
  public $city_municipality_id;
  public $label;

  public $edit_province_id;
  public $edit_city_municipality_id;
  public $edit_label;

  #modals
  public bool $addBarangayModal = false;
  public bool $editBarangayModal = false;
  public bool $updateBarangayStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

	public function boot(
		BarangayService $barangay_service,
    SelectOptionLibraryService $select_option_service,
	)
	{
		$this->barangay_service = $barangay_service;
    $this->select_option_service = $select_option_service;
	}

  public function mount(){
		// Initialize form fields
    $this->province_id = 0;
    $this->city_municipality_id = 0;
		$this->label = '';
	}

  public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function loadRecords
	public function barangay_lst(){
    try{
      if(!$this->search){
        $barangay_lst = $this->barangay_service->loadBarangayLst()->paginate(15);
        return $barangay_lst;
      }else{
        $barangay_lst = $this->barangay_service->loadBarangayLstByKeyword($this->search)->paginate(15);
        return $barangay_lst;
      }
    } catch(e){
     // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  #[Computed]
	// public function load province options
	public function load_province_options(){
		return $this->select_option_service->loadProvinceOptions();
	}

  #[Computed]
	// public function load city or municipality options
	public function load_city_municipality_options(){
		return $this->select_option_service->loadCityMunicipalityOptions();
	}

  // public function save barangay record changes
  public function save_barangay(){
    try{
      // Validation and saving logic
      $this->validate([
        'province_id' => 'required|not_in:0',
        'city_municipality_id' => 'required|not_in:0',
        'label' => 'required|string|max:255'
      ]);

      $exists = $this->barangay_service->addBarangayById($this->province_id, $this->city_municipality_id, $this->label, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new barangay successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['province_id', 'province_id']);
      $this->reset(['city_municipality_id', 'city_municipality_id']);
      $this->reset(['label', 'label']);

      // Close the modal
      $this->addBarangayModal = false;

      $this->barangay_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get barangay by id
	public function openEditBarangayModal(int $barangay_id){
    try{
      $this->resetValidation();  // clears validation errors
      $this->editBarangayModal = true;
      $this->barangay_id = $barangay_id;

      $result = $this->barangay_service->getBarangayById($this->barangay_id);

      foreach($result as $result){
        $this->edit_province_id = $result->province_id;
        $this->edit_city_municipality_id = $result->city_municipality_id;
        $this->edit_label = $result->label;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save barangay record changes
  public function save_barangay_record_changes(){
    try{
      // Validation and saving logic
      $this->validate([
        'edit_province_id' => 'required|not_in:0',
        'edit_city_municipality_id' => 'required|not_in:0',
        'edit_label' => 'required|string|max:255'
      ]);

      $exists = $this->barangay_service->updateBarangayById($this->barangay_id, $this->edit_province_id, $this->edit_city_municipality_id, $this->edit_label);

      if ($exists[0]->result_id == 0) {
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Updated barangay successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['barangay_id', 'barangay_id']);
      $this->reset(['edit_province_id', 'edit_province_id']);
      $this->reset(['edit_city_municipality_id', 'edit_city_municipality_id']);
      $this->reset(['edit_label', 'edit_label']);

      // Close the modal
      $this->editBarangayModal = false;

      $this->barangay_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateBarangayStatusModal(int $barangay_id, int $statuscode){
    try{
      $this->updateBarangayStatusModal = true;
      $this->barangay_id = $barangay_id;
      $this->statuscode = $statuscode;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_barangay_status($barangay_id, $statuscode){
    try{
      $result = $this->barangay_service->updateBarangayStatusById($barangay_id, $statuscode, auth()->user()->id);
      
      // // Toast
      if ($result[0]->result_id > 0) {
        // Optional: Show error to user
        $this->addMessage = 'Updated barangay status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }else{
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }

      $this->updateBarangayStatusModal = false;	
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}


	public function render(){
		return view('livewire.pages.barangay.barangay');
	}

}
