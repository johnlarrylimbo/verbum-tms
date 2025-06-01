<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class CongregationService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadCongregationLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_congregation_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting congregation list', 500, $exception);
			}
	}

	public function loadCongregationLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_congregation_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting congregation list by keyword', 500, $exception);
		}
	}

  public function addCongregation(string $param_abbreviation, string $param_description, int $param_user_id)
	{
		try {
			$abbreviation = $param_abbreviation ?? '';
      $description = $param_description ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_congregation_ins')
								->stored_procedure_params([':p_abbreviation, :p_description, :p_result_id'])
								->stored_procedure_values([ $abbreviation, $description, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new congragation:', [
										'abbreviation' => $abbreviation,
										'description' => $description,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding congregation', 500, $exception);
		}
	}

	public function getCongregationById(int $param_congregation_id)
	{
		try {
			$congregation_id = $param_congregation_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_congregation_by_id_sel')
								->stored_procedure_params([':p_congregation_id'])
								->stored_procedure_values([$congregation_id])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting congregation by id', 500, $exception);
		}
	}

	public function updateCongregationById(int $param_congregation_id, string $param_abbreviation, string $param_description, int $param_user_id)
	{
		try {
			$congregation_id = $param_congregation_id ?? 0;
			$abbreviation = $param_abbreviation ?? '';
			$description = $param_description ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_congregation_by_id_upd')
								->stored_procedure_params([':p_congregation_id, :p_abbreviation, :p_description, :result_id'])
								->stored_procedure_values([ $congregation_id, $abbreviation, $description, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated congragation:', [
										'congregation_id' => $congregation_id,
										'abbreviation' => $abbreviation,
										'description' => $description,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating congregation by id', 500, $exception);
		}
	}

	public function updateCongregationStatusById(int $param_congregation_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$congregation_id = $param_congregation_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_congregation_status_by_id_upd')
								->stored_procedure_params([':p_congregation_id, :p_statuscode, :result_id'])
								->stored_procedure_values([$congregation_id, $statuscode, 0])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated congregation status:', [
										'congregation_id' => $congregation_id, 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating congregation status by id', 500, $exception);
		}
	}
    
}
