<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;

class SelectOptionLibraryService extends Service
{
    protected SP $sp;

    public function __construct(SP $sp)
    {
        $this->sp = $sp;
    }
    //instantiate brand model class

    public function loadProvinceOptions()
    {
        try {
            $result = $this->sp
                ->stored_procedure('pr_datims_province_select_options')
                ->execute();

            return $result->stored_procedure_result();
        } catch (Exception $exception) {
            throw new Exception('Error getting province select options', 500, $exception);
        }
    }

    public function loadCityMunicipalityOptions()
    {
        try {
            $result = $this->sp
                ->stored_procedure('pr_datims_city_municipality_select_options')
                ->execute();

            return $result->stored_procedure_result();
        } catch (Exception $exception) {
            throw new Exception('Error getting city or municipality select options', 500, $exception);
        }
    }

    public function loadPriestOptions()
    {
        try {
            $result = $this->sp
                ->stored_procedure('pr_datims_priest_select_options')
                ->execute();

            return $result->stored_procedure_result();
        } catch (Exception $exception) {
            throw new Exception('Error getting priest select options', 500, $exception);
        }
    }

    public function loadCongregationOptions()
    {
        try {
            $result = $this->sp
                ->stored_procedure('pr_datims_congregation_select_options')
                ->execute();

            return $result->stored_procedure_result();
        } catch (Exception $exception) {
            throw new Exception('Error getting congregation select options', 500, $exception);
        }
    }

    public function loadDioceseOptions()
    {
        try {
            $result = $this->sp
                ->stored_procedure('pr_datims_diocese_select_options')
                ->execute();

            return $result->stored_procedure_result();
        } catch (Exception $exception) {
            throw new Exception('Error getting diocese select options', 500, $exception);
        }
    }

    public function loadVicariateByDioceseIdOptions(int $param_diocese_id)
    {
        try {
            $diocese_id = $param_diocese_id ?? 0;

            $result = $this->sp
                ->stored_procedure('pr_datims_vicariate_by_diocese_id_select_options')
                ->stored_procedure_params([' :p_diocese_id '])
				->stored_procedure_values([ $diocese_id ])
                ->execute();

            return $result->stored_procedure_result();
        } catch (Exception $exception) {
            throw new Exception('Error getting vicariate by diocese id select options', 500, $exception);
        }
    }

    public function loadParishesOptions()
    {
        try {

            $result = $this->sp
                ->stored_procedure('pr_datims_parishes_select_options')
                ->execute();

            return $result->stored_procedure_result();
        } catch (Exception $exception) {
            throw new Exception('Error getting parishes select options', 500, $exception);
        }
    }

    public function loadContractCategoryOptions()
    {
        try {

            $result = $this->sp
                ->stored_procedure('pr_datims_contract_category_select_options')
                ->execute();

            return $result->stored_procedure_result();
        } catch (Exception $exception) {
            throw new Exception('Error getting contract category select options', 500, $exception);
        }
    }

    public function loadContractTypeOptions()
    {
        try {

            $result = $this->sp
                ->stored_procedure('pr_datims_contract_type_select_options')
                ->execute();

            return $result->stored_procedure_result();
        } catch (Exception $exception) {
            throw new Exception('Error getting contract type select options', 500, $exception);
        }
    }
    
}
