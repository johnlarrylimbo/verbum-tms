<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class ContractService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadContractLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_contract_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting contract list', 500, $exception);
			}
	}

	public function loadContractLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_contract_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting contract list by keyword', 500, $exception);
		}
	}

  public function createContract(string $param_contract_no, int $param_client_id, int $param_contract_category_id, int $param_contract_type_id, int $param_contract_detail_id, string $param_contact_person, string $param_contact_person_designation, int $param_account_representative_id, string $param_contract_start, string $param_contract_end, string $param_contract_amount, $param_remarks, int $param_user_id)
	{
		try {
      $contract_no  = $param_contract_no ?? '';
			$client_id = $param_client_id ?? 0;
			$contract_category_id = $param_contract_category_id ?? 0;
			$contract_type_id = $param_contract_type_id ?? 0;
			$contract_detail_id = $param_contract_detail_id ?? 0;
			$contact_person  = $param_contact_person ?? '';
			$contact_person_designation  = $param_contact_person_designation ?? '';
			$account_representative_id = $param_account_representative_id ?? 0;
			$contract_start  = $param_contract_start ?? '';
			$contract_end  = $param_contract_end ?? '';
			$contract_amount  = $param_contract_amount ?? '';
			$remarks  = $param_remarks ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_contract_ins')
								->stored_procedure_params([':p_contract_no, :p_client_id, :p_contract_category_id, :p_contract_type_id, :p_contract_detail_id, :p_contact_person, :p_contact_person_designation, :p_account_representative_id, :p_contract_start, :p_contract_end, :p_contract_amount, :p_remarks, :result_contract_no'])
								->stored_procedure_values([ $contract_no, $client_id, $contract_category_id, $contract_type_id, $contract_detail_id, $contact_person, $contact_person_designation, $account_representative_id, $contract_start, $contract_end, $contract_amount, $remarks, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new contract:', [
                    'contract_no' => $contract_no,
                    'client_id' => $client_id,
										'contract_category_id' => $contract_category_id,
										'contract_type_id' => $contract_type_id,
										'contract_detail_id' => $contract_detail_id,
										'contact_person' => $contact_person,
										'contact_person_designation' => $contact_person_designation,
										'account_representative_id' => $account_representative_id,
										'contract_start' => $contract_start,
										'contract_end' => $contract_end,
										'contract_amount' => $contract_amount,
										'remarks' => $remarks,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding contract', 500, $exception);
		}
	}

	public function getContractForPaymentById(int $param_contract_id)
	{
		try {
			$contract_id = $param_contract_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_contract_form_payment_by_id_sel')
								->stored_procedure_params([':p_contract_id'])
								->stored_procedure_values([ $contract_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting role by id', 500, $exception);
		}
	}

	public function addContractCashPayment(int $param_contract_id, string $param_amount_to_be_paid, int $param_payment_type_id, string $param_amount, string $param_receipt_remarks, int $param_user_id)
	{
		try {
			$contract_id = $param_contract_id ?? 0;
			$amount_to_be_paid  = $param_amount_to_be_paid ?? '';
			$payment_type_id = $param_payment_type_id ?? 0;
			$amount  = $param_amount ?? '';
			$receipt_remarks  = $param_receipt_remarks ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_payment_cash_ins')
								->stored_procedure_params([':p_contract_id, :p_amount_to_be_paid, :p_payment_type_id, :p_amount, :p_receipt_remarks, :p_user_id, :result_or_id'])
								->stored_procedure_values([ $contract_id, $amount_to_be_paid, $payment_type_id, $amount, $receipt_remarks, $user_id, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new payment:', [
                    'contract_id' => $contract_id,
										'amount_to_be_paid' => $amount_to_be_paid,
                    'payment_type_id' => $payment_type_id,
										'amount' => $amount,
										'receipt_remarks' => $receipt_remarks,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding payment', 500, $exception);
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
