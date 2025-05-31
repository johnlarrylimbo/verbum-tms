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
    
}
