<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class IslandGroupService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadIslandGroupLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_island_group_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting island group list', 500, $exception);
			}
	}

	public function loadIslandGroupLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_island_group_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting island group list by keyword', 500, $exception);
		}
	}

  public function addIslandGroup(string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $abbreviation  = $param_abbreviation ?? '';
			$label  = $param_label ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_island_group_ins')
								->stored_procedure_params([':p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new island group:', [
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding island group', 500, $exception);
		}
	}

	public function getIslandGroupById(int $param_island_group_id)
	{
		try {
			$island_group_id = $param_island_group_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_island_group_by_id_sel')
								->stored_procedure_params([':p_island_group_id'])
								->stored_procedure_values([ $island_group_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting island group by id', 500, $exception);
		}
	}

	public function updateIslandGroupById(int $param_island_group_id, string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $island_group_id = $param_island_group_id ?? 0;
			$abbreviation = $param_abbreviation ?? '';
      $label = $param_label ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_island_group_by_id_upd')
								->stored_procedure_params([':p_island_group_id, :p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $island_group_id, $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated island group:', [
                    'island_group_id' => $island_group_id,
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating island group by id', 500, $exception);
		}
	}

	public function updateIslandGroupStatusById(int $param_island_group_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$island_group_id  = $param_island_group_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_island_group_status_by_id_upd')
								->stored_procedure_params([':p_island_group_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $island_group_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated island group status:', [
										'island_group_id ' => $island_group_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating island group status by id', 500, $exception);
		}
	}
    
}
