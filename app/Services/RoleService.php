<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class RoleService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadRoleLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_role_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting role list', 500, $exception);
			}
	}

	public function loadRoleLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_role_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting role list by keyword', 500, $exception);
		}
	}

  public function addRole(string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $abbreviation  = $param_abbreviation ?? '';
			$label  = $param_label ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_role_ins')
								->stored_procedure_params([':p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new role:', [
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding role', 500, $exception);
		}
	}

	public function getRoleById(int $param_role_id)
	{
		try {
			$role_id = $param_role_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_role_by_id_sel')
								->stored_procedure_params([':p_role_id'])
								->stored_procedure_values([ $role_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting role by id', 500, $exception);
		}
	}

	public function updateRoleById(int $param_role_id, string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $role_id = $param_role_id ?? 0;
			$abbreviation = $param_abbreviation ?? '';
      $label = $param_label ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_role_by_id_upd')
								->stored_procedure_params([':p_role_id, :p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $role_id, $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated role:', [
                    'role_id' => $role_id,
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating role by id', 500, $exception);
		}
	}

	public function updateRoleStatusById(int $param_role_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$role_id  = $param_role_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_role_status_by_id_upd')
								->stored_procedure_params([':p_role_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $role_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated role status:', [
										'role_id ' => $role_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating role status by id', 500, $exception);
		}
	}
    
}
