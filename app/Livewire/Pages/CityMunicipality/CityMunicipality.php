<?php

namespace App\Livewire\Pages\CityMunicipality;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\CityMunicipalityService;
use App\Services\LGUTypeService;
use App\Services\SelectOptionLibraryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class CityMunicipality extends Component
{

	use WithPagination;
	use Toast;

	#protected variables
	protected $city_municipality_service;
  protected $lgu_type_service;

  #public variables
  public $search;
  public $city_municipality_id;
  public $statuscode;

  public $country_id;
  public $province_id;
  public $region_id;
  public $lgu_type_id;
  public $label;
  public $latitude;
  public $longitude;

  public $lgu_type_abbreviation;
  public $lgu_type_label;

  public $edit_country_id;
  public $edit_province_id;
  public $edit_region_id;
  public $edit_lgu_type_id;
  public $edit_label;
  public $edit_latitude;
  public $edit_longitude;

  #modals
  public bool $addCityMunicipalityModal = false;
  public bool $editCityMunicipalityModal = false;
  public bool $updateCityMunicipalityStatusModal = false;
  public bool $addLGUTypeModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

  public $provinces_options = [];

	public function boot(
		CityMunicipalityService $city_municipality_service,
    LGUTypeService $lgu_type_service,
    SelectOptionLibraryService $select_option_service,
	)
	{
		$this->city_municipality_service = $city_municipality_service;
    $this->lgu_type_service = $lgu_type_service;
    $this->select_option_service = $select_option_service;
	}

	public function mount(){
		// Initialize form fields
    $this->country_id = 0;
    $this->province_id = 0;
    $this->region_id = 0;
    $this->lgu_type_id = 0;
    $this->label = '';
    $this->latitude = '';
    $this->longitude = '';

    $this->lgu_type_abbreviation = '';
    $this->lgu_type_label = '';
	}

	public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load city or municipality records
	public function city_municipality_lst(){
    try{
      if(!$this->search){
        $city_municipality_lst = $this->city_municipality_service->loadCityMunicipalityLst()->paginate(15);
        return $city_municipality_lst;
      }else{
        $city_municipality_lst = $this->city_municipality_service->loadCityMunicipalityLstByKeyword($this->search)->paginate(15);
        return $city_municipality_lst;
      }
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  #[Computed]
	// public function load country options
	public function load_country_options(){
		return $this->select_option_service->loadCountryOptions();
	}

  #[Computed]
	// public function load LGU Type options
	public function load_lgu_type_options(){
		return $this->select_option_service->loadLGUTypeOptions();
	}

  #[Computed]
	// public function load region options
	public function load_region_options(){
		return $this->select_option_service->loadRegionOptions();
	}

  //public function load provinces when region change
  public function regionChanged()
  {
    $region_id = $this->region_id;

    if (!$region_id) {
        $this->provinces_options = [];
        return;
    }

    // Update the property that holds vicariate options
    $this->provinces_options = $this->select_option_service->loadProvincesByRegionIdOptions($region_id);
  }

  // public function save city or municipality record changes
  public function save_city_municipality(){
    try{
      // Validation and saving logic
      $this->validate([
        'country_id' => 'required|not_in:0',
        'lgu_type_id' => 'required|not_in:0',
        'region_id' => 'required|not_in:0',
        'province_id' => 'required|not_in:0',
        'label' => 'required|string|max:255'
      ]);

      $exists = $this->city_municipality_service->addCityMunicipality($this->country_id, $this->lgu_type_id, $this->region_id, $this->province_id, $this->label, $this->latitude, $this->longitude, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new city or municipality successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['country_id', 'country_id']);
      $this->reset(['lgu_type_id', 'lgu_type_id']);
      $this->reset(['region_id', 'region_id']);
      $this->reset(['province_id', 'province_id']);
      $this->reset(['label', 'label']);
      $this->reset(['latitude', 'latitude']);
      $this->reset(['longitude', 'longitude']);

      // Close the modal
      $this->addCityMunicipalityModal = false;

      $this->city_municipality_lst();
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function get city or municipality by id
	public function openEditCityMunicipalityModal(int $city_municipality_id){
    try{
      $this->resetValidation();  // clears validation errors
      $this->editCityMunicipalityModal = true;
      $this->city_municipality_id = $city_municipality_id;

      $result = $this->city_municipality_service->getCityMunicipalityById($this->city_municipality_id);

      foreach($result as $result){
        $this->edit_country_id = $result->country_id;
        $this->edit_province_id = $result->province_id;
        $this->edit_region_id = $result->region_id;
        $this->edit_lgu_type_id = $result->local_gov_unit_type_id;
        $this->edit_label = $result->label;
        $this->edit_latitude = $result->latitude;
        $this->edit_longitude = $result->longitude;
      }

      $region_id = $this->edit_region_id;

      if (!$region_id) {
          $this->provinces_options = [];
          return;
      }

      // Update the property that holds vicariate options
      $this->provinces_options = $this->select_option_service->loadProvincesByRegionIdOptions($region_id);

    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  //public function load provinces when region change
  public function edit_regionChanged()
  {
    $region_id = $this->edit_region_id;

    if (!$region_id) {
        $this->provinces_options = [];
        return;
    }

    // Update the property that holds vicariate options
    $this->provinces_options = $this->select_option_service->loadProvincesByRegionIdOptions($region_id);
  }

  // public function save city or municipality record changes
  public function save_city_municipality_record_changes(){
    try{
      // Validation and saving logic
      $this->validate([
        'edit_country_id' => 'required|not_in:0',
        'edit_lgu_type_id' => 'required|not_in:0',
        'edit_region_id' => 'required|not_in:0',
        'edit_province_id' => 'required|not_in:0',
        'edit_label' => 'required|string|max:255'
      ]);

      $exists = $this->city_municipality_service->updateCityMunicipalityById($this->city_municipality_id, $this->edit_country_id, $this->edit_lgu_type_id, $this->edit_region_id, $this->edit_province_id, $this->edit_label, $this->edit_latitude, $this->edit_longitude, auth()->user()->id);

      if ($exists[0]->result_id == 0) {
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Updated city or municipality successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['city_municipality_id', 'city_municipality_id']);
      $this->reset(['edit_country_id', 'edit_country_id']);
      $this->reset(['edit_lgu_type_id', 'edit_lgu_type_id']);
      $this->reset(['edit_region_id', 'edit_region_id']);
      $this->reset(['edit_province_id', 'edit_province_id']);
      $this->reset(['edit_label', 'edit_label']);
      $this->reset(['edit_latitude', 'edit_latitude']);
      $this->reset(['edit_longitude', 'edit_longitude']);

      // Close the modal
      $this->editCityMunicipalityModal = false;

      $this->city_municipality_lst();

    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openUpdateCityMunicipalityStatusModal(int $city_municipality_id, int $statuscode){
    try{
      $this->updateCityMunicipalityStatusModal = true;
      $this->city_municipality_id = $city_municipality_id;
      $this->statuscode = $statuscode;
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function update_city_municipality_status($city_municipality_id, $statuscode){
    try{
      $result = $this->city_municipality_service->updateCityMunicipalityStatusById($city_municipality_id, $statuscode, auth()->user()->id);
      
      // // Toast
      if ($result[0]->result_id > 0) {
        // Optional: Show error to user
        $this->addMessage = 'Updated city or municipality status successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }else{
        // Optional: Show error to user
        $this->addMessage = 'Failed to update record status. Record does not exists in the database.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }

      $this->updateCityMunicipalityStatusModal = false;	
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while performing this action.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  // public function save lgu type record changes
  public function save_lgu_type(){
    try{
      // Validation and saving logic
      $this->validate([
        'lgu_type_abbreviation' => 'required|string|max:64',
        'lgu_type_label' => 'required|string|max:255',
      ]);

      $exists = $this->lgu_type_service->addLGUType($this->lgu_type_abbreviation, $this->lgu_type_label, auth()->user()->id);

      if ($exists[0]->result_id == 1) {
        // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
      }
      else{
        // Optional: Show error to user
        $this->addMessage = 'Added new LGU Type successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
      }

      // Optionally reset form fields after save
      $this->reset(['lgu_type_abbreviation', 'lgu_type_abbreviation']);
      $this->reset(['lgu_type_label', 'lgu_type_label']);

      // Close the modal
      $this->addLGUTypeModal = false;
      
    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while adding this new record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}


	public function render(){
		return view('livewire.pages.city-municipality.city-municipality');
	}

}
