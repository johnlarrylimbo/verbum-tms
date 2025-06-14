<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class DesignationService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadDesignationLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_designation_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting designation list', 500, $exception);
			}
	}

	public function loadDesignationLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_designation_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting designation list by keyword', 500, $exception);
		}
	}

  public function addDesignation(string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $abbreviation  = $param_abbreviation ?? '';
			$label  = $param_label ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_designation_ins')
								->stored_procedure_params([':p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new designation:', [
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding designation', 500, $exception);
		}
	}

	public function getDesignationById(int $param_designation_id)
	{
		try {
			$designation_id = $param_designation_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_designation_by_id_sel')
								->stored_procedure_params([':p_designation_id'])
								->stored_procedure_values([ $designation_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting designation by id', 500, $exception);
		}
	}

	public function updateDesignationById(int $param_designation_id, string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $designation_id = $param_designation_id ?? 0;
			$abbreviation = $param_abbreviation ?? '';
      $label = $param_label ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_designation_by_id_upd')
								->stored_procedure_params([':p_designation_id, :p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $designation_id, $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated designation:', [
                    'designation_id' => $designation_id,
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating designation by id', 500, $exception);
		}
	}

	public function updateDesignationStatusById(int $param_designation_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$designation_id  = $param_role_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_designation_status_by_id_upd')
								->stored_procedure_params([':p_designation_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $designation_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated designation status:', [
										'designation_id ' => $designation_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating designation status by id', 500, $exception);
		}
	}
    
}
