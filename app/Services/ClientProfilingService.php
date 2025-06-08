<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class ClientProfilingService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

  public function addClientProfile(int $param_client_type_id, string $param_client_name, $param_birthdate, $param_address_rm_flr_unit_bldg, $param_address_lot_block, string $param_address_street, $param_address_subdivision, int $param_country_id, int $param_region_id, int $param_province_id, int $param_city_municipality_id, int $param_barangay_id, $param_citizenship_id, $param_religion_id, string $param_email_address, $param_facebook_name, $param_facebook_profile_link, string $param_contact_number, int $param_parish_id, int $param_basic_ecclecial_community_id, $param_spouse_name, $param_spouse_birthdate, $param_wedding_date, $param_spouse_address_rm_flr_unit_bldg, $param_spouse_address_lot_block, $param_spouse_address_street, $param_spouse_address_subdivision, $param_spouse_country_id, $param_spouse_region_id, $param_spouse_province_id, $param_spouse_city_municipality_id, $param_spouse_barangay_id, $param_spouse_citizenship_id, $param_spouse_religion_id, int $param_user_id)
	{
		try {
			$client_type_id  = $param_client_type_id ?? 0;
      $client_name  = $param_client_name ?? '';
			$birthdate  = $param_birthdate ?? '';
			$address_rm_flr_unit_bldg  = $param_address_rm_flr_unit_bldg ?? '';
			$address_lot_block  = $param_address_lot_block ?? '';
			$address_street  = $param_address_street ?? '';
			$address_subdivision  = $param_address_subdivision ?? '';
			$country_id  = $param_country_id ?? 0;
			$region_id  = $param_region_id ?? 0;
			$province_id  = $param_province_id ?? 0;
			$city_municipality_id  = $param_city_municipality_id ?? 0;
			$barangay_id  = $param_barangay_id ?? 0;
			$citizenship_id  = $param_citizenship_id ?? 0;
			$religion_id  = $param_religion_id ?? 0;
			$email_address  = $param_email_address ?? '';
			$facebook_name  = $param_facebook_name ?? '';
			$facebook_profile_link  = $param_facebook_profile_link ?? '';
			$contact_number  = $param_contact_number ?? '';
			$parish_id  = $param_parish_id ?? 0;
			$basic_ecclecial_community_id  = $param_basic_ecclecial_community_id ?? 0;
			$spouse_name  = $param_spouse_name ?? '';
			$spouse_birthdate  = $param_spouse_birthdate ?? '';
			$wedding_date  = $param_wedding_date ?? '';
			$spouse_address_rm_flr_unit_bldg  = $param_spouse_address_rm_flr_unit_bldg ?? '';
			$spouse_address_lot_block  = $param_spouse_address_lot_block ?? '';
			$spouse_address_street  = $param_spouse_address_street ?? '';
			$spouse_address_subdivision  = $param_spouse_address_subdivision ?? '';
			$spouse_country_id  = $param_spouse_country_id ?? 0;
			$spouse_region_id  = $param_spouse_region_id ?? 0;
			$spouse_province_id  = $param_spouse_province_id ?? 0;
			$spouse_city_municipality_id  = $param_spouse_city_municipality_id ?? 0;
			$spouse_barangay_id  = $param_spouse_barangay_id ?? 0;
			$spouse_citizenship_id  = $param_spouse_citizenship_id ?? 0;
			$spouse_religion_id  = $param_spouse_religion_id ?? 0;
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_client_profile_ins')
								->stored_procedure_params([':p_client_type_id, :p_client_name, :p_birthdate, :p_address_rm_flr_unit_bldg, :p_address_lot_block, :p_address_street, :p_address_subdivision, :p_country_id, :p_region_id, :p_province_id, :p_city_municipality_id, :p_barangay_id, :p_citizenship_id, :p_religion_id, :p_email_address, :p_facebook_name, :p_facebook_profile_link, :p_contact_number, :p_parish_id, :p_basic_ecclecial_community_id, :p_spouse_name, :p_spouse_birthdate, :p_wedding_date, :p_spouse_address_rm_flr_unit_bldg, :p_spouse_address_lot_block, :p_spouse_address_street, :p_spouse_address_subdivision, :p_spouse_country_id, :p_spouse_region_id, :p_spouse_province_id, :p_spouse_city_municipality_id, :p_spouse_barangay_id, :p_spouse_citizenship_id, :p_spouse_religion_id, :result_id'])
								->stored_procedure_values([ $client_type_id, $client_name, $birthdate, $address_rm_flr_unit_bldg, $address_lot_block, $address_street, $address_subdivision, $country_id, $region_id, $province_id, $city_municipality_id, $barangay_id, $citizenship_id, $religion_id, $email_address, $facebook_name, $facebook_profile_link, $contact_number, $parish_id, $basic_ecclecial_community_id, $spouse_name, $spouse_birthdate, $wedding_date, $spouse_address_rm_flr_unit_bldg, $spouse_address_lot_block, $spouse_address_street, $spouse_address_subdivision, $spouse_country_id, $spouse_region_id, $spouse_province_id, $spouse_city_municipality_id, $spouse_barangay_id, $spouse_citizenship_id, $spouse_religion_id, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new client profile:', [
										'client_type_id' => $client_type_id,
                    'client_name' => $client_name,
                    'birthdate' => $birthdate,
										'address_rm_flr_unit_bldg' => $address_rm_flr_unit_bldg,
                    'address_lot_block' => $address_lot_block,
										'address_street' => $address_street,
                    'address_subdivision' => $address_subdivision,
										'country_id' => $country_id,
                    'region_id' => $region_id,
										'province_id' => $province_id,
                    'city_municipality_id' => $city_municipality_id,
										'barangay_id' => $barangay_id,
                    'citizenship_id' => $citizenship_id,
										'religion_id' => $religion_id,
                    'email_address' => $email_address,
										'facebook_name' => $facebook_name,
                    'facebook_profile_link' => $facebook_profile_link,
										'contact_number' => $contact_number,
                    'parish_id' => $parish_id,
										'basic_ecclecial_community_id' => $basic_ecclecial_community_id,
                    'spouse_name' => $spouse_name,
										'spouse_birthdate' => $spouse_birthdate,
                    'wedding_date' => $wedding_date,
										'spouse_address_rm_flr_unit_bldg' => $spouse_address_rm_flr_unit_bldg,
                    'spouse_address_lot_block' => $spouse_address_lot_block,
										'spouse_address_street' => $spouse_address_street,
                    'spouse_address_subdivision' => $spouse_address_subdivision,
										'spouse_country_id' => $spouse_country_id,
                    'spouse_region_id' => $spouse_region_id,
										'spouse_province_id' => $spouse_province_id,
                    'spouse_city_municipality_id' => $spouse_city_municipality_id,
										'spouse_barangay_id' => $spouse_barangay_id,
                    'spouse_citizenship_id' => $spouse_citizenship_id,
										'spouse_religion_id' => $spouse_religion_id,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding client profile', 500, $exception);
		}
	}

	public function getRoleById(int $param_role_id)
	{
		try {
			$role_id = $param_role_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_role_by_id_sel')
								->stored_procedure_params([':p_role_id'])
								->stored_procedure_values([ $role_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting role by id', 500, $exception);
		}
	}

	public function updateRoleById(int $param_role_id, string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $role_id = $param_role_id ?? 0;
			$abbreviation = $param_abbreviation ?? '';
      $label = $param_label ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_role_by_id_upd')
								->stored_procedure_params([':p_role_id, :p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $role_id, $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated role:', [
                    'role_id' => $role_id,
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating role by id', 500, $exception);
		}
	}

	public function updateRoleStatusById(int $param_role_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$role_id  = $param_role_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_role_status_by_id_upd')
								->stored_procedure_params([':p_role_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $role_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated role status:', [
										'role_id ' => $role_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating role status by id', 500, $exception);
		}
	}
    
}
