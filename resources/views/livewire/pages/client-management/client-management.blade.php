<div x-data="{ init: false }" x-init="if (!init) { init = true; $wire.client_profile_lst() }">
  <x-mary-header title="::.. Client List">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Client..."  wire:model.live="search"/>
      </x-slot:middle>
      {{-- <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addClientCategoryModal = true" />
      </x-slot:actions> --}}
  </x-mary-header>

  <x-mary-card>

    <div class="my-4">
      {{ $this->client_profile_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="5%">#</th>
            <th class="text-center bg-primary text-white" width="10%">Client Type</th>
            <th class="text-center bg-primary text-white" width="40%">Client Name</th>
            <th class="text-center bg-primary text-white" width="15%">Contact No.</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white" width="20%">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->client_profile_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="6">No client profile record(s) found.</td>
            </tr>
          @else
            @foreach ($this->client_profile_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="text-center vertical-align-top">{{ $result->row_num }}</td>
                <td class="text-center vertical-align-top" style="word-break: break-word;">{{ $result->client_type_label }}</td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">
                  <strong>{{ $result->client_name }}</strong> <br />
                  {{ $result->client_code }}<br />
                  {{ $result->email_address }}<br /><br />
                  Affiliated Parish : {{ $result->parish_name }}<br />
                  Affiliated GKK    : {{ $result->bec_name }}
                </td>
                <td class="text-center vertical-align-top" style="word-break: break-word;">{{ $result->telephone_number }}</td>
                <td class="text-center vertical-align-top">
                  @if($result->statuscode == 1)
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-green-600 text-white" />
                  @else
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-red-600 text-white" />
                  @endif
                </td>
                <td class="text-center vertical-align-top">
                  <x-mary-button icon="o-pencil-square" 
                                  wire:click="openEditClientProfileByIdWindow({{ $result->client_id }})" 
                                  wire:target="openEditClientProfileByIdWindow"
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />
                  <x-mary-button icon="o-magnifying-glass-circle" 
                                  wire:click="openViewClientProfileModal({{ $result->client_id }})" 
                                  wire:target="openViewClientProfileModal"
                                  spinner 
                                  class="bg-primary text-white btn-sm align-center" />
                   {{-- <x-mary-button icon="m-printer"
                                    wire:click="openUpdateClientCategoryStatusModal({{ $result->client_id }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    /> --}}
                  <x-mary-button 
                      icon="m-printer"
                      onclick="window.open('{{ url('/print-client-profile/' . $result->client_id) }}', '_blank')"
                      class="bg-enabled text-white btn-sm align-center"
                      spinner
                  />
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>

    <div class="my-4">
      {{ $this->client_profile_lst->links() }}
    </div>

  </x-mary-card>

  <x-mary-modal 
    wire:model="viewClientProfileModal" 
    wrapper-class="w-full max-w-none"
    class="custom-content"
    style="width:100% !important;"
  >
    @php $disabled = true; @endphp

    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">View Client Profile</h2>
    </div>
    <x-mary-card>

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
                wire:model="client_type_id" 
                :disabled="$disabled" />
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
              <x-mary-input label="Client Name" wire:model="client_name" id="client_name" class="uppercase" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 50% !important;">
              <x-mary-datetime label="Birthdate" wire:model="birthdate" id="birthdate" :disabled="$disabled" />
            </div>&nbsp;

          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">
            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Room/Floor/Unit/Bldg" wire:model="address_rm_flr_unit_bldg" id="address_rm_flr_unit_bldg" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Lot / Block" wire:model="address_lot_block" id="address_lot_block" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Street" wire:model="address_street" id="address_street" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Subdivision" wire:model="address_subdivision" id="address_subdivision" :disabled="$disabled" />
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
                wire:model="country_id"
                 :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Region"
                :options="$this->load_region_options"
                option-value="id"
                option-label="abbreviation"
                placeholder="Select a region"
                placeholder-value="0"
                wire:model="region_id"
                wire:change="regionChanged"
                 :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Province"
                :options="$provinces_options"
                option-value="id"
                option-label="label"
                placeholder="Select a province"
                placeholder-value="0"
                wire:model="province_id"
                wire:change="provinceChanged"
                 :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="City / Municipality"
                :options="$city_municipality_options"
                option-value="id"
                option-label="label"
                placeholder="Select a city or municipality"
                placeholder-value="0"
                wire:model="city_municipality_id"
                wire:change="citymunicipalityChanged"
                 :disabled="$disabled" />
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
                wire:model="barangay_id"
                 :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Citizenship"
                :options="$this->load_citizenship_options"
                option-value="id"
                option-label="label"
                placeholder="Select a citizenship"
                placeholder-value="0"
                wire:model="citizenship_id"
                 :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Religion"
                :options="$this->load_religion_options"
                option-value="id"
                option-label="label"
                placeholder="Select a religion"
                placeholder-value="0"
                wire:model="religion_id"
                 :disabled="$disabled" />
            </div>&nbsp;

          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Email Address" wire:model="email_address" id="email_address" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Facebook Name" wire:model="facebook_name" id="facebook_name" :disabled="$disabled" />
            </div>&nbsp;

          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="FB Profile Link" wire:model="facebook_profile_link" id="facebook_profile_link" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Contact No. (e.g. Mobile No. or Tel. No.)" wire:model="contact_number" id="contact_number" :disabled="$disabled" />
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
                wire:model="parish_id"
                wire:change="parishChanged"
                 :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Affiliated Basic Ecclesial Community (GKK)"
                :options="$bec_options"
                option-value="id"
                option-label="bec_name"
                placeholder="Select a GKK"
                placeholder-value="0"
                wire:model="basic_ecclecial_community_id"
                 :disabled="$disabled" />
            </div>&nbsp;

          </div>

        </div>

      </x-mary-card>

      <x-mary-card title="Spouse Information" subtitle="Client spouse profile information" separator progress-indicator>

        <div class="p-4 bg-white rounded-lg shadow-md">
          
          <div class="flex flex-col md:flex-row md:items-end md:gap-4">
            <div class="w-full md:w-1/2 mb-1" style="margin-bottom: 4px;">
              <x-mary-input label="Spouse Name" wire:model="spouse_name" id="spouse_name" class="uppercase" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 45% !important;">
              <x-mary-datetime label="Birthdate" wire:model="spouse_birthdate" id="spouse_birthdate" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 45% !important;">
              <x-mary-datetime label="Wedding Date" wire:model="wedding_date" id="wedding_date" :disabled="$disabled" />
            </div>
          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">
            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Room/Floor/Unit/Bldg" wire:model="spouse_address_rm_flr_unit_bldg" id="spouse_address_rm_flr_unit_bldg" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Lot / Block" wire:model="spouse_address_lot_block" id="spouse_address_lot_block" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Street" wire:model="spouse_address_street" id="spouse_address_street" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Subdivision" wire:model="spouse_address_subdivision" id="spouse_address_subdivision" :disabled="$disabled" />
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
                wire:model="spouse_country_id" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Region"
                :options="$this->load_region_options"
                option-value="id"
                option-label="abbreviation"
                placeholder="Select a region"
                placeholder-value="0"
                wire:model="spouse_region_id"
                wire:change="spouseRegionChanged" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Province"
                :options="$spouse_provinces_options"
                option-value="id"
                option-label="label"
                placeholder="Select a province"
                placeholder-value="0"
                wire:model="spouse_province_id"
                wire:change="spouseProvinceChanged" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="City / Municipality"
                :options="$city_municipality_options"
                option-value="id"
                option-label="label"
                placeholder="Select a city or municipality"
                placeholder-value="0"
                wire:model="spouse_city_municipality_id"
                wire:change="spouseCityMunicipalityChanged" :disabled="$disabled" />
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
                wire:model="spouse_barangay_id" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Citizenship"
                :options="$this->load_citizenship_options"
                option-value="id"
                option-label="label"
                placeholder="Select a citizenship"
                placeholder-value="0"
                wire:model="spouse_citizenship_id" :disabled="$disabled" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Religion"
                :options="$this->load_religion_options"
                option-value="id"
                option-label="label"
                placeholder="Select a religion"
                placeholder-value="0"
                wire:model="spouse_religion_id" :disabled="$disabled" />
            </div>&nbsp;

          </div>

        </div>

      </x-mary-card>

    </x-mary-card>
  </x-mary-modal>


  <!-- 
    Loader goes here 
  -->
  <x-livewire-loader target="client_profile_lst" message="Please wait while the system loads all client profile records for you..." />

  <x-livewire-loader target="openEditClientProfileByIdWindow,openViewClientProfileModal" message="Please wait while the system retrieves the record for you..." />

</div>