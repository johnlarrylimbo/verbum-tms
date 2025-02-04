<?php

namespace App\Livewire\Pages\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

use App\Services\HealthClaimCategoryService;

#[Layout('layouts.auth-page')]
class WireAuth extends Component
{
    // protected $health_claim_category_service;
    // public $search_query = '';
    // public $filter_modal = false;
    // public $selected_health_claim_categories = [];

    // public function boot(HealthClaimCategoryService $health_claim_category_service)
    // {
    //     $this->health_claim_category_service = $health_claim_category_service;
    // }

    // #[Computed]
    // public function health_claim_categories()
    // {
    //     return $this->health_claim_category_service->loadHealthClaimCategories();
    // }

    // public function searchHandler()
    // {
    //     $this->dispatch(
    //         'search',
    //         $this->search_query ?? '',
    //         $this->selected_health_claim_categories ?? []
    //     );

    //     if ($this->filter_modal) $this->filter_modal = false;
    // }

    public function render()
    {
        return view('livewire.pages.auth.login');
    }
}
