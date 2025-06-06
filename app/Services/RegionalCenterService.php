<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class RegionalCenterService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadRegionalCenterLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_regional_center_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting regional centers list', 500, $exception);
			}
	}

	public function loadRegionalCenterLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_regional_center_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting regional centers list by keyword', 500, $exception);
		}
	}

  public function addRegionalCenter(string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $abbreviation  = $param_abbreviation ?? '';
			$label  = $param_label ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_regional_center_ins')
								->stored_procedure_params([':p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new regional centers:', [
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding regional centers', 500, $exception);
		}
	}

	public function getRegionalCenterById(int $param_regional_center_id)
	{
		try {
			$regional_center_id = $param_regional_center_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_regional_center_by_id_sel')
								->stored_procedure_params([':p_regional_center_id'])
								->stored_procedure_values([ $regional_center_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting regional center by id', 500, $exception);
		}
	}

	public function updateRegionalCenterById(int $param_regional_center_id, string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $regional_center_id = $param_regional_center_id ?? 0;
			$abbreviation = $param_abbreviation ?? '';
      $label = $param_label ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_regional_center_by_id_upd')
								->stored_procedure_params([':p_regional_center_id, :p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $regional_center_id, $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated regional center:', [
                    'regional_center_id' => $regional_center_id,
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating regional center by id', 500, $exception);
		}
	}

	public function updateRegionalCenterStatusById(int $param_regional_center_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$regional_center_id  = $param_regional_center_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_regional_center_status_by_id_upd')
								->stored_procedure_params([':p_regional_center_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $regional_center_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated regional center status:', [
										'regional_center_id ' => $regional_center_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating regional center status by id', 500, $exception);
		}
	}
    
}
