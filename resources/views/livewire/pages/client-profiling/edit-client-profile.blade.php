<div>
  <x-mary-header title="::..Edit Client Profile">
      {{-- <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Roles..."  wire:model.live="search"/>
      </x-slot:middle> --}}
      {{-- <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addRoleModal = true" />
      </x-slot:actions> --}}
  </x-mary-header>

  {{-- {{ session('client_id') }} --}}

  @if ($showMessageToast)
    <div 
      x-data="{ show: true }" 
      x-show="show" 
      x-init="setTimeout(() => { show = false; @this.set('showMessageToast', false) }, 3000)" 
      x-transition 
      class="fixed top-4 right-4 z-50"
    >
      <x-mary-alert 
        :icon="$is_success ? 's-check-circle' : 'c-x-circle'" 
        :class="$is_success ? 'alert-success text-white' : 'bg-danger text-white'"
      >
        {{ $addMessage }}
      </x-mary-alert>
    </div>
  @endif


  <x-mary-card>

    <x-mary-form wire:submit.prevent="save_client_profile" no-separator>

      <x-mary-input type="hidden" wire:model="client_id" id="client_id" />

      <x-mary-card title="Personal Profile" subtitle="Client personal profile information" separator progress-indicator>

        <div class="p-4 bg-white rounded-lg shadow-md">

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Client Type"
                :options="$this->load_client_type_options"
                option-value="id"
                option-label="label"
                placeholder="Select a client type"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="client_type_id" />
            </div>&nbsp;

            <div class="w-full md:w-1/2 mb-1" style="margin-bottom: 4px;">
              &nbsp;
            </div>

            <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 50% !important;">
              &nbsp;
            </div>&nbsp;
            
          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">
            <div class="w-full md:w-1/2 mb-1" style="margin-bottom: 4px;">
              <x-mary-input label="Client Name" wire:model="client_name" id="client_name" class="uppercase" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 50% !important;">
              <x-mary-datetime label="Birthdate" wire:model="birthdate" id="birthdate" />
            </div>&nbsp;

          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">
            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Room/Floor/Unit/Bldg" wire:model="address_rm_flr_unit_bldg" id="address_rm_flr_unit_bldg" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Lot / Block" wire:model="address_lot_block" id="address_lot_block" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Street" wire:model="address_street" id="address_street" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Subdivision" wire:model="address_subdivision" id="address_subdivision" />
            </div>&nbsp;
            
          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Country"
                :options="$this->load_country_options"
                option-value="id"
                option-label="label"
                placeholder="Select a country"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="country_id" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Region"
                :options="$this->load_region_options"
                option-value="id"
                option-label="abbreviation"
                placeholder="Select a region"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="region_id"
                wire:change="regionChanged" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Province"
                :options="$provinces_options"
                option-value="id"
                option-label="label"
                placeholder="Select a province"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="province_id"
                wire:change="provinceChanged" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="City / Municipality"
                :options="$city_municipality_options"
                option-value="id"
                option-label="label"
                placeholder="Select a city or municipality"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="city_municipality_id"
                wire:change="citymunicipalityChanged" />
            </div>&nbsp;
            
          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">
            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Barangay"
                :options="$barangay_options"
                option-value="id"
                option-label="label"
                placeholder="Select a barangay"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="barangay_id" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Citizenship"
                :options="$this->load_citizenship_options"
                option-value="id"
                option-label="label"
                placeholder="Select a citizenship"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="citizenship_id" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Religion"
                :options="$this->load_religion_options"
                option-value="id"
                option-label="label"
                placeholder="Select a religion"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="religion_id" />
            </div>&nbsp;

          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Email Address" wire:model="email_address" id="email_address" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Facebook Name" wire:model="facebook_name" id="facebook_name" />
            </div>&nbsp;

          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="FB Profile Link" wire:model="facebook_profile_link" id="facebook_profile_link" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Contact No. (e.g. Mobile No. or Tel. No.)" wire:model="contact_number" id="contact_number" />
            </div>&nbsp;

          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Affiliated Parish"
                :options="$this->load_parish_options"
                option-value="id"
                option-label="parish_name"
                placeholder="Select a parish"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="parish_id"
                wire:change="parishChanged" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Affiliated Basic Ecclesial Community (GKK)"
                :options="$bec_options"
                option-value="id"
                option-label="bec_name"
                placeholder="Select a GKK"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="basic_ecclecial_community_id" />
            </div>&nbsp;

          </div>

        </div>

      </x-mary-card>

      <x-mary-card title="Spouse Information" subtitle="Client spouse profile information" separator progress-indicator>

        <div class="p-4 bg-white rounded-lg shadow-md">
          
          <div class="flex flex-col md:flex-row md:items-end md:gap-4">
            <div class="w-full md:w-1/2 mb-1" style="margin-bottom: 4px;">
              <x-mary-input label="Spouse Name" wire:model="spouse_name" id="spouse_name" class="uppercase" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 45% !important;">
              <x-mary-datetime label="Birthdate" wire:model="spouse_birthdate" id="spouse_birthdate" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 45% !important;">
              <x-mary-datetime label="Wedding Date" wire:model="wedding_date" id="wedding_date" />
            </div>
          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">
            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Room/Floor/Unit/Bldg" wire:model="spouse_address_rm_flr_unit_bldg" id="spouse_address_rm_flr_unit_bldg" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Lot / Block" wire:model="spouse_address_lot_block" id="spouse_address_lot_block" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Street" wire:model="spouse_address_street" id="spouse_address_street" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Subdivision" wire:model="spouse_address_subdivision" id="spouse_address_subdivision" />
            </div>&nbsp;
          </div>

          

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">
            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Country"
                :options="$this->load_country_options"
                option-value="id"
                option-label="label"
                placeholder="Select a country"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="spouse_country_id" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Region"
                :options="$this->load_region_options"
                option-value="id"
                option-label="abbreviation"
                placeholder="Select a region"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="spouse_region_id"
                wire:change="spouseRegionChanged" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Province"
                :options="$spouse_provinces_options"
                option-value="id"
                option-label="label"
                placeholder="Select a province"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="spouse_province_id"
                wire:change="spouseProvinceChanged" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="City / Municipality"
                :options="$city_municipality_options"
                option-value="id"
                option-label="label"
                placeholder="Select a city or municipality"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="spouse_city_municipality_id"
                wire:change="spouseCityMunicipalityChanged" />
            </div>&nbsp;
            
          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">
            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Barangay"
                :options="$barangay_options"
                option-value="id"
                option-label="label"
                placeholder="Select a barangay"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="spouse_barangay_id" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Citizenship"
                :options="$this->load_citizenship_options"
                option-value="id"
                option-label="label"
                placeholder="Select a citizenship"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="spouse_citizenship_id" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Religion"
                :options="$this->load_religion_options"
                option-value="id"
                option-label="label"
                placeholder="Select a religion"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="spouse_religion_id" />
            </div>&nbsp;

          </div>

        </div>

      </x-mary-card>

      <x-slot:actions>
          <x-mary-button label="Cancel" onclick="window.location.href='{{ url('client-management') }}'" />
          <x-mary-button 
                  label="Save Record" 
                  class="btn-primary" 
                  type="submit" 
                  spinner="save_client_profile"
                  wire:target="save_client_profile" />
      </x-slot:actions>

    </x-mary-form>

  </x-mary-card>


</div>