<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class ProvinceService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadProvinceLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_province_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting province list', 500, $exception);
			}
	}

	public function loadProvinceLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_province_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting province list by keyword', 500, $exception);
		}
	}

  public function addProvince(string $param_label, string $param_island_group_id, string $param_region_id, int $param_user_id)
	{
		try {
      $label  = $param_label ?? '';
			$island_group_id  = $param_island_group_id ?? 0;
      $region_id  = $param_region_id ?? 0;
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_province_ins')
								->stored_procedure_params([':p_label, :p_island_group_id, :p_region_id, :result_id'])
								->stored_procedure_values([ $label, $island_group_id, $region_id, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new province:', [
                    'label' => $label,
                    'island_group_id' => $island_group_id,
                    'region_id' => $region_id,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding province', 500, $exception);
		}
	}

	public function getProvinceById(int $param_province_id)
	{
		try {
			$province_id = $param_province_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_province_by_id_sel')
								->stored_procedure_params([':p_province_id'])
								->stored_procedure_values([ $province_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting province by id', 500, $exception);
		}
	}

	public function updateProvinceById(int $param_province_id, string $param_label, string $param_island_group_id, string $param_region_id, int $param_user_id)
	{
		try {
      $province_id = $param_province_id ?? 0;
			$label = $param_label ?? '';
      $island_group_id = $param_island_group_id ?? 0;
      $region_id = $param_region_id ?? 0;
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_province_by_id_upd')
								->stored_procedure_params([':p_province_id, :p_label, :p_island_group_id, :p_region_id, :result_id'])
								->stored_procedure_values([ $province_id, $label, $island_group_id, $region_id, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated province:', [
                    'province_id' => $province_id,
                    'label' => $label,
                    'island_group_id' => $island_group_id,
                    'region_id' => $region_id,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating province by id', 500, $exception);
		}
	}

	public function updateProvinceStatusById(int $param_province_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$province_id  = $param_province_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_province_status_by_id_upd')
								->stored_procedure_params([':p_province_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $province_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated province status:', [
										'province_id ' => $province_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating province status by id', 500, $exception);
		}
	}
    
}
