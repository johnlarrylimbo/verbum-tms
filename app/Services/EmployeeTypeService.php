<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class EmployeeTypeService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadEmployeeTypeLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_employee_type_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting employee type list', 500, $exception);
			}
	}

	public function loadEmployeeTypeLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_employee_type_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting employee type list by keyword', 500, $exception);
		}
	}

  public function addEmployeeType(string $param_label, int $param_user_id)
	{
		try {
			$label  = $param_label ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_employee_type_ins')
								->stored_procedure_params([':p_label, :result_id'])
								->stored_procedure_values([ $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new employee type:', [
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding employee type', 500, $exception);
		}
	}

	public function getEmployeeTypeById(int $param_employee_type_id)
	{
		try {
			$employee_type_id = $param_employee_type_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_empployee_type_by_id_sel')
								->stored_procedure_params([':p_employee_type_id'])
								->stored_procedure_values([ $employee_type_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting employee type by id', 500, $exception);
		}
	}

	public function updateEmployeeTypeById(int $param_employee_type_id, string $param_label, int $param_user_id)
	{
		try {
      $employee_type_id = $param_employee_type_id ?? 0;
			$label = $param_label ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_employee_type_by_id_upd')
								->stored_procedure_params([':p_employee_type_id, :p_label, :result_id'])
								->stored_procedure_values([ $employee_type_id, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated employee type:', [
                    'employee_type_id' => $employee_type_id,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating employee type by id', 500, $exception);
		}
	}

	public function updateEmployeeTypeStatusById(int $param_employee_type_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$employee_type_id  = $param_employee_type_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_employee_type_status_by_id_upd')
								->stored_procedure_params([':p_employee_type_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $employee_type_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated employee type status:', [
										'employee_type_id ' => $employee_type_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating employee type status by id', 500, $exception);
		}
	}
    
}
