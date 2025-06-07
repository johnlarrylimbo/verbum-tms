<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class CitizenshipService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadCitizenshipLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_citizenship_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting citizenship list', 500, $exception);
			}
	}

	public function loadCitizenshipLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_citizenship_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting citizenship list by keyword', 500, $exception);
		}
	}

  public function addCitizenship(string $param_abbreviation, string $param_label, string $param_nationality, int $param_user_id)
	{
		try {
      $abbreviation  = $param_abbreviation ?? '';
      $label  = $param_label ?? '';
			$nationality  = $param_nationality ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_citizenship_ins')
								->stored_procedure_params([':p_abbreviation, :p_label, :p_nationality, :result_id'])
								->stored_procedure_values([ $abbreviation, $label, $nationality, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new citizenship:', [
                    'abbreviation' => $abbreviation,
                    'label' => $label,
                    'nationality' => $nationality,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding citizenship', 500, $exception);
		}
	}

	public function getCitizenshipById(int $param_citizenship_id)
	{
		try {
			$citizenship_id = $param_citizenship_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_citizenship_by_id_sel')
								->stored_procedure_params([':p_citizenship_id'])
								->stored_procedure_values([ $citizenship_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting citizenship by id', 500, $exception);
		}
	}

	public function updateCitizenshipById(int $param_citizenship_id, string $param_abbreviation, string $param_label, string $param_nationality, int $param_user_id)
	{
		try {
      $citizenship_id = $param_citizenship_id ?? 0;
      $abbreviation = $param_abbreviation ?? '';
			$label = $param_label ?? '';
      $nationality = $param_nationality ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_citizenship_by_id_upd')
								->stored_procedure_params([':p_citizenship_id, :p_abbreviation, :p_label, :p_nationality, :result_id'])
								->stored_procedure_values([ $citizenship_id, $abbreviation, $label, $nationality, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated citizenship:', [
                    'citizenship_id' => $citizenship_id,
                    'abbreviation' => $abbreviation,
                    'label' => $label,
                    'nationality' => $nationality,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating citizenship by id', 500, $exception);
		}
	}

	public function updateCitizenshipStatusById(int $param_citizenship_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$citizenship_id  = $param_citizenship_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_citizenship_status_by_id_upd')
								->stored_procedure_params([':p_citizenship_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $citizenship_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated citizenship status:', [
										'citizenship_id ' => $citizenship_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating citizenship status by id', 500, $exception);
		}
	}
    
}
