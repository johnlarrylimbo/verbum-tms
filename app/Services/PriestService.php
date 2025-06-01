<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class PriestService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadPriestLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_priest_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting priest list', 500, $exception);
			}
	}

	public function loadPriestLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_priest_lst_by_keyword')
									->stored_procedure_params([':keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting priest list by keyword', 500, $exception);
		}
	}

	public function addPriest(string $param_firstname, $param_middlename, string $param_lastname, int $param_congregation_id, int $param_user_id)
	{
		try {
			$firstname = $param_firstname ?? '';
			$middlename = $param_middlename ?? '';
			$lastname = $param_lastname ?? '';
			$congregation_id = $param_congregation_id ?? 0;
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_priest_ins')
								->stored_procedure_params([':p_firstname, :p_middlename, :p_lastname, :p_congregation_id, :p_result_id'])
								->stored_procedure_values([$firstname, $middlename, $lastname, $congregation_id, 0])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new priest:', [
										'firstname' => $firstname,
										'middlename' => $middlename,
										'lastname' => $lastname,
										'congregation_id' => $congregation_id,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding priest record', 500, $exception);
		}
	}

	public function getPriestById(int $param_priest_id)
	{
		try {
			$priest_id = $param_priest_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_priest_by_id_sel')
								->stored_procedure_params([':p_priest_id'])
								->stored_procedure_values([$priest_id])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting priest by id', 500, $exception);
		}
	}

	public function updatePriestById(int $param_priest_id, string $param_firstname, $param_middlename, string $param_lastname, int $param_congregation_id, int $param_user_id)
	{
		try {
			$priest_id = $param_priest_id ?? 0;
			$firstname = $param_firstname ?? '';
			$middlename = $param_middlename ?? '';
			$lastname = $param_lastname ?? '';
			$congregation_id = $param_congregation_id ?? 0;
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_priest_by_id_upd')
								->stored_procedure_params([':p_priest_id, :p_lastname, :p_firstname, :p_middlename, :p_congregation_id, :result_id'])
								->stored_procedure_values([ $priest_id, $lastname, $firstname, $middlename, $congregation_id, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated priest:', [
										'priest_id' => $priest_id,
										'firstname' => $firstname,
										'middlename' => $middlename,
										'lastname' => $lastname,
										'congregation_id' => $congregation_id,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating priest by id', 500, $exception);
		}
	}

	public function updatePriestStatusById(int $param_priest_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$priest_id = $param_priest_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_priest_status_by_id_upd')
								->stored_procedure_params([':p_priest_id, :p_statuscode, :result_id'])
								->stored_procedure_values([$priest_id, $statuscode, 0])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated priest status:', [
										'priest_id' => $priest_id, 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating priest status by id', 500, $exception);
		}
	}

	
    
}
