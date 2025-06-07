<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class CityMunicipalityService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadCityMunicipalityLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_city_municipality_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting city/municipality list', 500, $exception);
			}
	}

	public function loadCityMunicipalityLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_city_municipality_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting city/municipality list by keyword', 500, $exception);
		}
	}

  public function addCityMunicipality(int $param_country_id, int $param_lgu_type_id, int $param_region_id, int $param_province_id, string $param_label, $param_latitude, $param_longitude, int $param_user_id)
	{
		try {
      $country_id = $param_country_id ?? 0;
      $lgu_type_id = $param_lgu_type_id ?? 0;
      $region_id = $param_region_id ?? 0;
      $province_id = $param_province_id ?? 0;
      $label  = $param_label ?? '';
      $latitude  = $param_latitude ?? '';
      $longitude  = $param_longitude ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_city_municipality_ins')
								->stored_procedure_params([':p_country_id, :p_lgu_type_id, :p_region_id, :p_province_id, :p_label, :p_latitude, :p_longitude, :result_id'])
								->stored_procedure_values([ $country_id, $lgu_type_id, $region_id, $province_id, $label, $latitude, $longitude, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new city/municipality:', [
                    'country_id' => $country_id,
                    'lgu_type_id' => $lgu_type_id,
                    'region_id' => $region_id,
                    'province_id' => $province_id,
                    'label' => $label,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding city/municipality', 500, $exception);
		}
	}

	public function getCityMunicipalityById(int $param_city_municipality_id)
	{
		try {
			$city_municipality_id = $param_city_municipality_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_city_municipality_by_id_sel')
								->stored_procedure_params([':p_city_municipality_id'])
								->stored_procedure_values([ $city_municipality_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting city/municipality by id', 500, $exception);
		}
	}

	public function updateCityMunicipalityById(int $param_city_municipality_id, int $param_country_id, int $param_lgu_type_id, int $param_region_id, int $param_province_id, string $param_label, $param_latitude, $param_longitude, int $param_user_id)
	{
		try {
      $city_municipality_id = $param_city_municipality_id ?? 0;
      $country_id = $param_country_id ?? 0;
      $lgu_type_id = $param_lgu_type_id ?? 0;
      $region_id = $param_region_id ?? 0;
      $province_id = $param_province_id ?? 0;
      $label  = $param_label ?? '';
      $latitude  = $param_latitude ?? '';
      $longitude  = $param_longitude ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_city_municipality_by_id_upd')
								->stored_procedure_params([':p_city_municipality_id, :p_country_id, :p_lgu_type_id, :p_region_id, :p_province_id, :p_label, :p_latitude, :p_longitude, :result_id'])
								->stored_procedure_values([ $city_municipality_id, $country_id, $lgu_type_id, $region_id, $province_id, $label, $latitude, $longitude, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated city/municipality:', [
                    'city_municipality_id' => $city_municipality_id,
                    'country_id' => $country_id,
                    'lgu_type_id' => $lgu_type_id,
                    'region_id' => $region_id,
                    'province_id' => $province_id,
                    'label' => $label,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating city/municipality by id', 500, $exception);
		}
	}

	public function updateCityMunicipalityStatusById(int $param_city_municipality_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$city_municipality_id  = $param_city_municipality_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_city_municipality_status_by_id_upd')
								->stored_procedure_params([':p_city_municipality_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $city_municipality_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated city/municipality status:', [
										'city_municipality_id ' => $city_municipality_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating city/municipality status by id', 500, $exception);
		}
	}
    
}
