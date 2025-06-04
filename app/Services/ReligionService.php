<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class ReligionService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadReligionLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_religion_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting religion list', 500, $exception);
			}
	}

	public function loadReligionLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_religion_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting religion list by keyword', 500, $exception);
		}
	}

  public function addReligion(string $param_label, int $param_user_id)
	{
		try {
			$label  = $param_label ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_religion_ins')
								->stored_procedure_params([':p_label, :result_id'])
								->stored_procedure_values([ $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new religion:', [
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding religion', 500, $exception);
		}
	}

	public function getReligionById(int $param_religion_id)
	{
		try {
			$religion_id = $param_religion_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_religion_by_id_sel')
								->stored_procedure_params([':p_religion_id'])
								->stored_procedure_values([ $religion_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting religion by id', 500, $exception);
		}
	}

	public function updateReligionById(int $param_religion_id, string $param_label, int $param_user_id)
	{
		try {
      $religion_id = $param_religion_id ?? 0;
			$label = $param_label ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_religion_by_id_upd')
								->stored_procedure_params([':p_religion_id, :p_label, :result_id'])
								->stored_procedure_values([ $religion_id, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated religion:', [
                    'religion_id' => $religion_id,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating religion by id', 500, $exception);
		}
	}

	public function updateReligionStatusById(int $param_religion_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$religion_id  = $param_religion_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_religion_status_by_id_upd')
								->stored_procedure_params([':p_religion_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $religion_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated religion status:', [
										'religion_id ' => $religion_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating religion status by id', 500, $exception);
		}
	}
    
}
