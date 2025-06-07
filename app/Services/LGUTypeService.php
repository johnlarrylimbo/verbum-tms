<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class LGUTypeService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadLGUTypeLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_lgu_type_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting LGU type list', 500, $exception);
			}
	}

	public function loadLGUTypeLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_lgu_type_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting LGU type list by keyword', 500, $exception);
		}
	}

  public function addLGUType(string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $abbreviation  = $param_abbreviation ?? '';
      $label  = $param_label ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_lgu_type_ins')
								->stored_procedure_params([':p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new LGU type:', [
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding LGU type', 500, $exception);
		}
	}

	public function getLGUTypeById(int $param_lgu_type_id)
	{
		try {
			$lgu_type_id = $param_lgu_type_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_lgu_type_by_id_sel')
								->stored_procedure_params([':p_lgu_type_id'])
								->stored_procedure_values([ $lgu_type_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting LGU type by id', 500, $exception);
		}
	}

	public function updateLGUTypeById(int $param_lgu_type_id, string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $lgu_type_id = $param_lgu_type_id ?? 0;
      $abbreviation = $param_abbreviation ?? '';
			$label = $param_label ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_lgu_type_by_id_upd')
								->stored_procedure_params([':p_lgu_type_id, :p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $lgu_type_id, $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated LGU type:', [
                    'lgu_type_id' => $lgu_type_id,
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating LGU type by id', 500, $exception);
		}
	}

	public function updateLGUTypeStatusById(int $param_lgu_type_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$lgu_type_id  = $param_lgu_type_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_lgu_type_status_by_id_upd')
								->stored_procedure_params([':p_lgu_type_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $lgu_type_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated LGU type status:', [
										'lgu_type_id ' => $lgu_type_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating LGU type status by id', 500, $exception);
		}
	}
    
}
