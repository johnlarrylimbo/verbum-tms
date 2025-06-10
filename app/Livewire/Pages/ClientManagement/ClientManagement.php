<?php

namespace App\Livewire\Pages\ClientManagement;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\DB;

use App\Services\ClientManagementService;
use App\Services\ClientProfilingService;
use App\Services\SelectOptionLibraryService;

use Livewire\WithPagination;
use Mary\Traits\Toast;

use session;

#[Lazy]
#[Layout('layouts.app')]
class ClientManagement extends Component
{

	use WithPagination;
	use Toast;

	#protected variables
	protected $client_management_service;
  protected $client_profiling_service;
  protected $select_option_service;

  #public variables
  public $search;
  public $client_id;
  public $statuscode;

  // client information
  public $client_type_id, $client_name, $birthdate, $address_rm_flr_unit_bldg, $address_lot_block, $address_street, $address_subdivision, $country_id, $region_id, $province_id, $city_municipality_id, $barangay_id, $citizenship_id;
  public $religion_id, $email_address, $facebook_name, $facebook_profile_link, $contact_number, $parish_id, $basic_ecclecial_community_id;

  // spouse information
  public $spouse_name, $spouse_birthdate, $wedding_date, $spouse_address_rm_flr_unit_bldg, $spouse_address_lot_block, $spouse_address_street, $spouse_address_subdivision, $spouse_country_id;
  public $spouse_region_id, $spouse_province_id, $spouse_city_municipality_id, $spouse_barangay_id, $spouse_citizenship_id, $spouse_religion_id;

  #modals
  public bool $viewClientProfileModal = false;
  // public bool $editRoleModal = false;
  // public bool $updateRoleStatusModal = false;

  #toast messages
  public bool $showMessageToast = false;
  public bool $is_success = false;
	public string $addMessage = '';

  public $provinces_options = [];
  public $spouse_provinces_options = [];
  public $city_municipality_options = [];
  public $spouse_city_municipality_options = [];
  public $barangay_options = [];
  public $spouse_barangay_options = [];
  public $bec_options = [];

	public function boot(
		ClientManagementService $client_management_service,
    ClientProfilingService $client_profiling_service,
    SelectOptionLibraryService $select_option_service,
	)
	{
		$this->client_management_service = $client_management_service;
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
	// public function load client records
	public function client_profile_lst(){
    try{
      if(!$this->search){
        $client_profile_lst = $this->client_management_service->loadClientProfileLst()->paginate(15);
        return $client_profile_lst;
      }else{
        $client_profile_lst = $this->client_management_service->loadClientProfileLstByKeyword($this->search)->paginate(15);
        return $client_profile_lst;
      }
    } catch(e){
     // Optional: Show error to user
      $this->addMessage = 'Failed to load. An error occured while loading records.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  public function openEditClientProfileByIdWindow($client_id)
	{ 
		session()->put('client_id', $client_id);
		$this->redirect(route('client.view_client_profile_by_id'));
	}

  // public function get barangay by id
	public function openViewClientProfileModal(int $client_id){
    try{
      $this->resetValidation();  // clears validation errors
      $this->viewClientProfileModal = true;
      $this->client_id = $client_id;

      $result = $this->client_profiling_service->loadClientProfileById($this->client_id);

      foreach($result as $result){
        $this->client_type_id = $result->client_type_id;
        $this->client_name = $result->client_name;
        $this->birthdate = $result->birthdate;
        $this->address_rm_flr_unit_bldg = $result->address_rm_flr_unit_bldg;
        $this->address_lot_block = $result->address_lot_block;
        $this->address_street = $result->address_street;
        $this->address_subdivision = $result->address_subdivision;
        $this->country_id = $result->country_id;
        $this->region_id = $result->region_id;
        $this->province_id = $result->province_id;
        $this->city_municipality_id = $result->city_municipality_id;
        $this->barangay_id = $result->barangay_id;
        $this->citizenship_id = $result->citizenship_id;
        $this->religion_id = $result->religion_id;
        $this->email_address = $result->email_address;
        $this->facebook_name = $result->facebook_name;
        $this->facebook_profile_link = $result->facebook_profile_link;
        $this->contact_number = $result->telephone_number;
        $this->parish_id = $result->parish_id;
        $this->basic_ecclecial_community_id = $result->basic_ecclecial_community_id;
        $this->spouse_name = $result->spouse_name;
        $this->spouse_birthdate = $result->spouse_birthdate;
        $this->wedding_date = $result->wedding_date;
        $this->spouse_address_rm_flr_unit_bldg = $result->spouse_address_rm_flr_unit_bldg;
        $this->spouse_address_lot_block = $result->spouse_address_lot_block;
        $this->spouse_address_street = $result->spouse_address_street;
        $this->spouse_address_subdivision = $result->spouse_address_subdivision;
        $this->spouse_country_id = $result->spouse_country_id;
        $this->spouse_region_id = $result->spouse_region_id;
        $this->spouse_province_id = $result->spouse_province_id;
        $this->spouse_city_municipality_id = $result->spouse_city_municipality_id;
        $this->spouse_barangay_id = $result->spouse_barangay_id;
        $this->spouse_citizenship_id = $result->spouse_citizenship_id;
        $this->spouse_religion_id = $result->spouse_religion_id;

        $region_id = $this->region_id;

        if (!$region_id) {
            $this->provinces_options = [];
            return;
        }

        // Update the property that holds vicariate options
        $this->provinces_options = $this->select_option_service->loadProvincesByRegionIdOptions($region_id);

        $province_id = $this->province_id;

        if (!$province_id) {
            $this->city_municipality_options = [];
            return;
        }

        // Update the property that holds vicariate options
        $this->city_municipality_options = $this->select_option_service->loadCityMunicipalityByProvinceIdOptions($province_id);

        $city_municipality_id = $this->city_municipality_id;

        if (!$city_municipality_id) {
            $this->barangay_options = [];
            return;
        }

        // Update the property that holds vicariate options
        $this->barangay_options = $this->select_option_service->loadBarangayByCityMunicipalityIdOptions($city_municipality_id);

        $parish_id = $this->parish_id;

        if (!$parish_id) {
            $this->bec_options = [];
            return;
        }

        // Update the property that holds vicariate options
        $this->bec_options = $this->select_option_service->loadBasicEcclesialCommunityByParishIdOptions($parish_id);



        $spouse_region_id = $this->spouse_region_id;

        if (!$spouse_region_id) {
            $this->spouse_provinces_options = [];
            return;
        }

        // Update the property that holds vicariate options
        $this->spouse_provinces_options = $this->select_option_service->loadProvincesByRegionIdOptions($spouse_region_id);

        $spouse_province_id = $this->spouse_province_id;

        if (!$spouse_province_id) {
            $this->spouse_city_municipality_options = [];
            return;
        }

        // Update the property that holds vicariate options
        $this->spouse_city_municipality_options = $this->select_option_service->loadCityMunicipalityByProvinceIdOptions($spouse_province_id);

        $spouse_city_municipality_id = $this->spouse_city_municipality_id;

        if (!$spouse_city_municipality_id) {
            $this->spouse_barangay_options = [];
            return;
        }

        // Update the property that holds vicariate options
        $this->spouse_barangay_options = $this->select_option_service->loadBarangayByCityMunicipalityIdOptions($city_municipality_id);
      }

    } catch(e){
      // Optional: Show error to user
      $this->addMessage = 'Action Failed! An error occured while retrieving this record.';
      $this->showMessageToast = true;
      $this->is_success = false;
    }
	}

  #[Computed]
	// public function load client type options
	public function load_client_type_options(){
		return $this->select_option_service->loadClientTypeOptions();
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

  //public function load provinces when region change
  public function spouseRegionChanged()
  {
    $spouse_region_id = $this->spouse_region_id;

    if (!$spouse_region_id) {
        $this->spouse_provinces_options = [];
        return;
    }

    // Update the property that holds vicariate options
    $this->spouse_provinces_options = $this->select_option_service->loadProvincesByRegionIdOptions($spouse_region_id);
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

  //public function load provinces when region change
  public function spouseProvinceChanged()
  {
    $spouse_province_id = $this->spouse_province_id;

    if (!$spouse_province_id) {
        $this->spouse_city_municipality_options = [];
        return;
    }

    // Update the property that holds vicariate options
    $this->spouse_city_municipality_options = $this->select_option_service->loadCityMunicipalityByProvinceIdOptions($spouse_province_id);
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

  //public function load barangay when city or municipality change
  public function spouseCityMunicipalityChanged()
  {
    $spouse_city_municipality_id = $this->spouse_city_municipality_id;

    if (!$spouse_city_municipality_id) {
        $this->spouse_barangay_options = [];
        return;
    }

    // Update the property that holds vicariate options
    $this->spouse_barangay_options = $this->select_option_service->loadBarangayByCityMunicipalityIdOptions($city_municipality_id);
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


	public function render(){
		return view('livewire.pages.client-management.client-management');
	}

}
