<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class ContractCategoryTypeService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadContractCategoryTypeLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_contract_category_type_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting contract category type list', 500, $exception);
			}
	}

	public function loadContractCategoryTypeLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_contract_category_type_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting contract category type list by keyword', 500, $exception);
		}
	}

  public function addContractCategoryType(int $param_contract_category_id, int $param_contract_type_id, string $param_label, int $param_user_id)
	{
		try {
      $contract_category_id = $param_contract_category_id ?? 0;
      $contract_type_id = $param_contract_type_id ?? 0;
			$label  = $param_label ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_contract_category_type_ins')
								->stored_procedure_params([':p_contract_category_id, :p_contract_type_id, :p_label, :result_id'])
								->stored_procedure_values([ $contract_category_id, $contract_type_id, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new contract category type:', [
                    'contract_category_id' => $contract_category_id,
                    'contract_type_id' => $contract_type_id,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding contract category type', 500, $exception);
		}
	}

	public function getContractCategoryTypeById(int $param_contract_category_type_id)
	{
		try {
			$contract_category_type_id = $param_contract_category_type_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_contract_category_type_by_id_sel')
								->stored_procedure_params([':p_contract_category_type_id'])
								->stored_procedure_values([ $contract_category_type_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting contract category type by id', 500, $exception);
		}
	}

	public function updateContractCategoryTypeById(int $param_contract_category_type_id, int $param_contract_category_id, int $param_contract_type_id, string $param_label, int $param_user_id)
	{
		try {
      $contract_category_type_id = $param_contract_category_type_id ?? 0;
      $contract_category_id = $param_contract_category_id ?? 0;
			$contract_type_id = $param_contract_type_id ?? 0;
			$label = $param_label ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_contract_category_type_by_id_upd')
								->stored_procedure_params([':p_contract_category_type_id, :p_contract_category_id, :p_contract_type_id, :p_label, :result_id'])
								->stored_procedure_values([ $contract_category_type_id, $contract_category_id, $contract_type_id, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated contract category type:', [
                    'contract_category_type_id' => $contract_category_type_id,
                    'contract_category_id' => $contract_category_id,
										'contract_type_id' => $contract_type_id,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating contract category type by id', 500, $exception);
		}
	}

	public function updateContractCategoryTypeStatusById(int $param_contract_category_type_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$contract_category_type_id  = $param_contract_category_type_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_contract_category_type_status_by_id_upd')
								->stored_procedure_params([':p_contract_category_type_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $contract_category_type_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated contract category type status:', [
										'contract_category_type_id ' => $contract_category_type_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating contract category type status by id', 500, $exception);
		}
	}
    
}
