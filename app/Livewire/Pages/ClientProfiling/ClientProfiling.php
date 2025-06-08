<?php

namespace App\Livewire\Pages\ClientProfiling;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\ClientProfilingService;
use App\Services\SelectOptionLibraryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class ClientProfiling extends Component
{

	use WithPagination;
	use Toast;

	#protected variables
	protected $client_profiling_service;
  protected $select_option_service;

  #public variables
  public $search;
  public $client_id;
  public $statuscode;

  public $same_as_client_address_details = false;

  // client information
  public $client_type_id, $client_name, $birthdate, $address_rm_flr_unit_bldg, $address_lot_block, $address_street, $address_subdivision, $country_id, $region_id, $province_id, $city_municipality_id, $barangay_id, $citizenship_id;
  public $religion_id, $email_address, $facebook_name, $facebook_profile_link, $contact_number, $parish_id, $basic_ecclecial_community_id;

  // spouse information
  public $spouse_name, $spouse_birthdate, $wedding_date, $spouse_address_rm_flr_unit_bldg, $spouse_address_lot_block, $spouse_address_street, $spouse_address_subdivision, $spouse_country_id;
  public $spouse_region_id, $spouse_province_id, $spouse_city_municipality_id, $spouse_barangay_id, $spouse_citizenship_id, $spouse_religion_id;

  public $edit_client_name;

  #modals
  // public bool $addRoleModal = false;
  // public bool $editRoleModal = false;
  // public bool $updateRoleStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

  public $provinces_options = [];
  public $city_municipality_options = [];
  public $barangay_options = [];
  public $bec_options = [];

	public function boot(
		ClientProfilingService $client_profiling_service,
    SelectOptionLibraryService $select_option_service,
	)
	{
		$this->client_profiling_service = $client_profiling_service;
    $this->select_option_service = $select_option_service;
	}

	public function mount(){
		// Initialize form fields
    $this->client_type_id = 0;
    $this->client_name = '';
    $this->birthdate = '';
    $this->address_rm_flr_unit_bldg = '';
    $this->address_lot_block = '';
    $this->address_street = '';
    $this->address_subdivision = '';
    $this->country_id = 0;
    $this->region_id = 0;
    $this->province_id = 0;
    $this->city_municipality_id = 0;
    $this->barangay_id = 0;
    $this->citizenship_id = 0;
    $this->religion_id = 0;
    $this->email_address = '';
    $this->facebook_name = '';
    $this->facebook_profile_link = '';
    $this->contact_number = '';
    $this->parish_id = 0;
    $this->basic_ecclecial_community_id = 0;
    $this->spouse_name = '';
    $this->spouse_birthdate = '';
    $this->wedding_date = '';
    $this->spouse_address_rm_flr_unit_bldg = '';
    $this->spouse_address_lot_block = '';
    $this->spouse_address_street = '';
    $this->spouse_address_subdivision = '';
    $this->spouse_country_id = 0;
    $this->spouse_region_id = 0;
    $this->spouse_province_id = 0;
    $this->spouse_city_municipality_id = 0;
    $this->spouse_barangay_id = 0;
    $this->spouse_citizenship_id = 0;
    $this->spouse_religion_id = 0;
	}

	public function updatedSearch()
	{
    // Reset pagination when the search term is updated
    $this->resetPage();
	}

  #[Computed]
	// public function load country options
	public function load_country_options(){
		return $this->select_option_service->loadCountryOptions();
	}

  #[Computed]
	// public function load region options
	public function load_region_options(){
		return $this->select_option_service->loadRegionOptions();
	}

  #[Computed]
	// public function load citizenship options
	public function load_citizenship_options(){
		return $this->select_option_service->loadCitizenshipOptions();
	}

  #[Computed]
	// public function load religion options
	public function load_religion_options(){
		return $this->select_option_service->loadReligionOptions();
	}

  #[Computed]
	// public function load parish options
	public function load_parish_options(){
		return $this->select_option_service->loadParishesOptions();
	}

  #[Computed]
	// public function load client type options
	public function load_client_type_options(){
		return $this->select_option_service->loadClientTypeOptions();
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

  //public function load city or municipality when province change
  public function provinceChanged()
  {
    $province_id = $this->province_id;

    if (!$province_id) {
        $this->city_municipality_options = [];
        return;
    }

    // Update the property that holds vicariate options
    $this->city_municipality_options = $this->select_option_service->loadCityMunicipalityByProvinceIdOptions($province_id);
  }

  //public function load barangay when city or municipality change
  public function citymunicipalityChanged()
  {
    $city_municipality_id = $this->city_municipality_id;

    if (!$city_municipality_id) {
        $this->barangay_options = [];
        return;
    }

    // Update the property that holds vicariate options
    $this->barangay_options = $this->select_option_service->loadBarangayByCityMunicipalityIdOptions($city_municipality_id);
  }

  //public function load basic ecclesial community when parish change
  public function parishChanged()
  {
    $parish_id = $this->parish_id;

    if (!$parish_id) {
        $this->bec_options = [];
        return;
    }

    // Update the property that holds vicariate options
    $this->bec_options = $this->select_option_service->loadBasicEcclesialCommunityByParishIdOptions($parish_id);
  }

  // Automatically triggered when checkbox value changes
public function handleSameAsClientAddressDetailsChange()
{
    if ($this->same_as_client_address_details) {
        $this->copyClientAddressesDetails();
    } else {
        $this->clearSpouseAddressDetails();
    }
}

public function copyClientAddressesDetails()
{
    $this->spouse_address_rm_flr_unit_bldg = $this->address_rm_flr_unit_bldg;
    $this->spouse_address_lot_block = $this->address_lot_block;
    $this->spouse_address_street = $this->address_street;
    $this->spouse_address_subdivision = $this->address_subdivision;
    $this->spouse_country_id = $this->country_id;
    $this->spouse_region_id = $this->region_id;
    $this->spouse_province_id = $this->province_id;
    $this->spouse_city_municipality_id = $this->city_municipality_id;
    $this->spouse_barangay_id = $this->barangay_id;
    $this->spouse_citizenship_id = $this->citizenship_id;
    $this->spouse_religion_id = $this->religion_id;
}

public function clearSpouseAddressDetails()
{
    $this->spouse_address_rm_flr_unit_bldg = '';
    $this->spouse_address_lot_block = '';
    $this->spouse_address_street = '';
    $this->spouse_address_subdivision = '';
    $this->spouse_country_id = null;
    $this->spouse_region_id = null;
    $this->spouse_province_id = null;
    $this->spouse_city_municipality_id = null;
    $this->spouse_barangay_id = null;
    $this->spouse_citizenship_id = null;
    $this->spouse_religion_id = null;
}

  // public function save client profile record
  public function save_client_profile(){
		// Validation and saving logic

		$this->validate([
      'client_type_id' => 'required|not_in:0',
			'client_name' => 'required|string|max:2048',
      'address_street' => 'required|string|max:256',
      'country_id' => 'required|not_in:0',
      'region_id' => 'required|not_in:0',
      'province_id' => 'required|not_in:0',
      'city_municipality_id' => 'required|not_in:0',
      'barangay_id' => 'required|not_in:0',
      'email_address' => 'required|string|max:512',
      'contact_number' => 'required|string|max:512',
      'parish_id' => 'required|not_in:0',
      'basic_ecclecial_community_id' => 'required|not_in:0'
		]);

    $exists = $this->client_profiling_service->addClientProfile($this->client_type_id, strtoupper($this->client_name), $this->birthdate, strtoupper($this->address_rm_flr_unit_bldg), strtoupper($this->address_lot_block), strtoupper($this->address_street), strtoupper($this->address_subdivision), $this->country_id, $this->region_id, $this->province_id, $this->city_municipality_id, $this->barangay_id, $this->citizenship_id, $this->religion_id, strtoupper($this->email_address), strtoupper($this->facebook_name), $this->facebook_profile_link, $this->contact_number, $this->parish_id, $this->basic_ecclecial_community_id, strtoupper($this->spouse_name), $this->spouse_birthdate, $this->wedding_date, strtoupper($this->spouse_address_rm_flr_unit_bldg), strtoupper($this->spouse_address_lot_block), strtoupper($this->spouse_address_street), strtoupper($this->spouse_address_subdivision), $this->spouse_country_id, $this->spouse_region_id, $this->spouse_province_id, $this->spouse_city_municipality_id, $this->spouse_barangay_id, $this->spouse_citizenship_id, $this->spouse_religion_id, auth()->user()->id);

		if ($exists[0]->result_id == 1) {
      // Optional: Show error to user
        $this->addMessage = 'Record already exist. Please try adding new record.';
        $this->showMessageToast = true;
        $this->is_success = false;
		}
		else{
      // Optional: Show error to user
        $this->addMessage = 'Added new client profile successfully.';
        $this->showMessageToast = true;
        $this->is_success = true;
		}

		// Optionally reset form fields after save
    $this->reset(['client_type_id', 'client_type_id']);
		$this->reset(['client_name', 'client_name']);
    $this->reset(['birthdate', 'birthdate']);
		$this->reset(['address_rm_flr_unit_bldg', 'address_rm_flr_unit_bldg']);
    $this->reset(['address_lot_block', 'address_lot_block']);
		$this->reset(['address_street', 'address_street']);
    $this->reset(['address_subdivision', 'address_subdivision']);
		$this->reset(['country_id', 'country_id']);
    $this->reset(['region_id', 'region_id']);
		$this->reset(['province_id', 'province_id']);
    $this->reset(['city_municipality_id', 'city_municipality_id']);
		$this->reset(['barangay_id', 'barangay_id']);
    $this->reset(['citizenship_id', 'citizenship_id']);
		$this->reset(['religion_id', 'religion_id']);
    $this->reset(['email_address', 'email_address']);
		$this->reset(['facebook_name', 'facebook_name']);
    $this->reset(['facebook_profile_link', 'facebook_profile_link']);
		$this->reset(['contact_number', 'contact_number']);
    $this->reset(['parish_id', 'parish_id']);
		$this->reset(['basic_ecclecial_community_id', 'basic_ecclecial_community_id']);
    $this->reset(['spouse_name', 'spouse_name']);
		$this->reset(['spouse_birthdate', 'spouse_birthdate']);
    $this->reset(['wedding_date', 'wedding_date']);
		$this->reset(['spouse_address_rm_flr_unit_bldg', 'spouse_address_rm_flr_unit_bldg']);
    $this->reset(['spouse_address_lot_block', 'spouse_address_lot_block']);
		$this->reset(['spouse_address_street', 'spouse_address_street']);
    $this->reset(['spouse_address_subdivision', 'spouse_address_subdivision']);
		$this->reset(['spouse_country_id', 'spouse_country_id']);
    $this->reset(['spouse_region_id', 'spouse_region_id']);
		$this->reset(['spouse_province_id', 'spouse_province_id']);
    $this->reset(['spouse_city_municipality_id', 'spouse_city_municipality_id']);
		$this->reset(['spouse_barangay_id', 'spouse_barangay_id']);
    $this->reset(['spouse_citizenship_id', 'spouse_citizenship_id']);
		$this->reset(['spouse_religion_id', 'spouse_religion_id']);

	}

  // // public function get role by id
	// public function openEditRoleModal(int $role_id){
  //   $this->resetValidation();  // clears validation errors
	// 	$this->editRoleModal = true;
	// 	$this->role_id = $role_id;

  //   $result = $this->role_service->getRoleById($this->role_id);

	// 	foreach($result as $result){
	// 		$this->edit_abbreviation = $result->abbreviation;
  //     $this->edit_label = $result->label;
	// 	}
	// }

  // // public function save role record changes
  // public function save_role_record_changes(){
	// 	// Validation and saving logic
  //   $this->validate([
	// 		'edit_abbreviation' => 'required|string|max:255',
  //     'edit_label' => 'required|string|max:255'
	// 	]);

  //   $exists = $this->role_service->updateRoleById($this->role_id, $this->edit_abbreviation, $this->edit_label, auth()->user()->id);

	// 	if ($exists[0]->result_id == 0) {
  //     $this->showErrorMessage = true;
	// 	}
	// 	else{
  //     $this->showSuccessMessage = true;
	// 	}

	// 	// Optionally reset form fields after save
  //   $this->reset(['role_id', 'role_id']);
	// 	$this->reset(['edit_abbreviation', 'edit_abbreviation']);
  //   $this->reset(['edit_label', 'edit_label']);

	// 	// Close the modal
	// 	$this->editRoleModal = false;

	// 	$this->role_lst();
	// }

  // public function openUpdateRoleStatusModal(int $role_id, int $statuscode){
	// 	$this->updateRoleStatusModal = true;
	// 	$this->role_id = $role_id;
  //   $this->statuscode = $statuscode;
	// }

  // public function update_role_status($role_id, $statuscode){

  //   $result = $this->role_service->updateRoleStatusById($role_id, $statuscode, auth()->user()->id);
		
	// 	// // Toast
  //   if ($result[0]->result_id > 0) {
  //     $this->showSuccessMessage = true;
  //   }else{
  //     $this->showErrorMessage = true;
  //   }

	// 	$this->updateRoleStatusModal = false;	
	// }


	public function render(){
		return view('livewire.pages.client-profiling.client-profiling');
	}

}
