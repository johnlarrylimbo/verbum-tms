<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class DioceseVicariateService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadVicariateLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_vicariate_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting vicariate list', 500, $exception);
			}
	}

	public function loadVicariateLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_vicariate_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting vicariate list by keyword', 500, $exception);
		}
	}

  public function addVicariate(int $param_diocese_id, string $param_label, int $param_user_id)
	{
		try {
      $diocese_id = $param_diocese_id ?? 0;
			$label  = $param_label ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_vicariate_ins')
								->stored_procedure_params([':p_diocese_id, :p_label, :result_id'])
								->stored_procedure_values([ $diocese_id, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new vicariate:', [
                    'diocese_id' => $diocese_id,
										'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding vicariate', 500, $exception);
		}
	}

	public function getVicariateById(int $param_vicariate_id)
	{
		try {
			$vicariate_id = $param_vicariate_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_vicariate_by_id_sel')
								->stored_procedure_params([':p_vicariate_id'])
								->stored_procedure_values([ $vicariate_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting vicariate by id', 500, $exception);
		}
	}

	public function updateVicariateById(int $param_vicariate_id, string $param_label, int $param_diocese_id, int $param_user_id)
	{
		try {
			$vicariate_id = $param_vicariate_id ?? 0;
			$label = $param_label ?? '';
      $diocese_id = $param_diocese_id ?? 0;
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_vicariate_by_id_upd')
								->stored_procedure_params([':p_vicariate_id, :p_label, :p_diocese_id, :result_id'])
								->stored_procedure_values([ $vicariate_id, $label, $diocese_id, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated vicariate:', [
                    'vicariate_id' => $vicariate_id,
										'label' => $label,
										'diocese_id' => $diocese_id,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating vicariate by id', 500, $exception);
		}
	}

	public function updateVicariateStatusById(int $param_vicariate_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$vicariate_id = $param_vicariate_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_vicariate_status_by_id_upd')
								->stored_procedure_params([':p_vicariate_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $vicariate_id, $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated vicariate status:', [
										'vicariate_id' => $vicariate_id, 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating vicariate status by id', 500, $exception);
		}
	}
    
}
