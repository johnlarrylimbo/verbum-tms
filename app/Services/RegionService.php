<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class RegionService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadRegionLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_region_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting region list', 500, $exception);
			}
	}

	public function loadRegionLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_region_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting region list by keyword', 500, $exception);
		}
	}

  public function addRegion(int $param_island_group_id, int $param_regional_center_id, string $param_numerals, string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $island_group_id = $param_island_group_id ?? 0;
      $regional_center_id = $param_regional_center_id ?? 0;
      $numerals  = $param_numerals ?? '';
      $abbreviation  = $param_abbreviation ?? '';
			$label  = $param_label ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_region_ins')
								->stored_procedure_params([':p_island_group_id, :p_regional_center_id, :p_numerals, :p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $island_group_id, $regional_center_id, $numerals, $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new region:', [
                    'island_group_id' => $island_group_id,
                    'regional_center_id' => $regional_center_id,
                    'numerals' => $numerals,
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding region', 500, $exception);
		}
	}

	public function getRegionById(int $param_region_id)
	{
		try {
			$region_id = $param_region_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_region_by_id_sel')
								->stored_procedure_params([':p_region_id'])
								->stored_procedure_values([ $region_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting region by id', 500, $exception);
		}
	}

	public function updateRegionById(int $param_region_id, int $param_island_group_id, int $param_regional_center_id, string $param_numerals, string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $region_id = $param_region_id ?? 0;
      $island_group_id = $param_island_group_id ?? 0;
      $regional_center_id = $param_regional_center_id ?? 0;
      $numerals = $param_numerals ?? '';
			$abbreviation = $param_abbreviation ?? '';
      $label = $param_label ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_region_by_id_upd')
								->stored_procedure_params([':p_region_id, :p_island_group_id, :p_regional_center_id, :p_numerals, :p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $region_id, $island_group_id, $regional_center_id, $numerals, $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated region:', [
                    'region_id' => $region_id,
                    'island_group_id' => $island_group_id,
                    'regional_center_id' => $regional_center_id,
                    'numerals' => $numerals,
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating region by id', 500, $exception);
		}
	}

	public function updateRegionStatusById(int $param_region_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$region_id  = $param_region_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_region_status_by_id_upd')
								->stored_procedure_params([':p_region_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $region_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated region status:', [
										'region_id ' => $region_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating region status by id', 500, $exception);
		}
	}
    
}
