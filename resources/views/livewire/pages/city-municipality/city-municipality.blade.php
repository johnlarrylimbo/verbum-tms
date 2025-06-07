<div x-data="{ init: false }" x-init="if (!init) { init = true; $wire.city_municipality_lst() }">
  <x-mary-header title="SystemLib :: City / Municipality">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search City/Municipality..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addCityMunicipalityModal = true" />
      </x-slot:actions>
  </x-mary-header>


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

    <div class="my-4">
      {{ $this->city_municipality_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="5%">#</th>
            <th class="text-center bg-primary text-white" width="15%">Code</th>
            <th class="text-center bg-primary text-white" width="30%">Description</th>
            <th class="text-center bg-primary text-white" width="15%">Region</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->city_municipality_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="5">No city or municipality record(s) found.</td>
            </tr>
          @else
            @foreach ($this->city_municipality_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="align-top text-center">{{ $result->row_num }}</td>
                <td class="text-center vertical-align-top" style="word-break: break-word;">{{ $result->city_municipality_code }}</td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">
                  <strong>{{ $result->label }}</strong> <br /><br />
                  Province  : {{ $result->province_label ?: '-' }}<br />
                  LGU Type  : {{ $result->lgu_type_label ?: '-' }}<br />
                  Country   : {{ $result->country_label ?: '-' }}
                </td>
                <td class="text-center vertical-align-top" style="word-break: break-word;">{{ $result->region_abbreviation ?: '-' }}</td>
                <td class="text-center vertical-align-top">
                  @if($result->statuscode == 1)
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-green-600 text-white" />
                  @else
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-red-600 text-white" />
                  @endif
                </td>
                <td class="text-center vertical-align-top">
                  <x-mary-button icon="o-pencil-square" 
                                  wire:click="openEditCityMunicipalityModal({{ $result->city_municipality_id }})" 
                                  wire:target="openEditCityMunicipalityModal"
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdateCityMunicipalityStatusModal({{ $result->city_municipality_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdateCityMunicipalityStatusModal({{ $result->city_municipality_id }},{{ $result->statuscode }})"
                                    class="bg-disabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @endif
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>

    <div class="my-4">
      {{ $this->city_municipality_lst->links() }}
    </div>

  </x-mary-card>


  <x-mary-modal wire:model="addCityMunicipalityModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add LGU Type</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_city_municipality" no-separator>

      <x-mary-select
						label="Country"
						:options="$this->load_country_options"
						option-value="id"
						option-label="label"
						placeholder="Select a country"
						placeholder-value=""
						hint="Select one, please."
						wire:model="country_id" />

      <div class="flex items-center gap-2">
        <x-mary-select
              label="LGU Type"
              :options="$this->load_lgu_type_options"
              option-value="id"
              option-label="label"
              placeholder="Select a LGU Type"
              placeholder-value=""
              hint="Select one, please."
              wire:model="lgu_type_id" />

        <button 
                    type="button"
                    class="btn btn-sm btn-primary" 
                    wire:click="$set('addLGUTypeModal', true)"
                    >+ Add</button>
      </div>

      <x-mary-select
						label="Region"
						:options="$this->load_region_options"
						option-value="id"
						option-label="abbreviation"
						placeholder="Select a region"
						placeholder-value=""
						hint="Select one, please."
						wire:model="region_id"
            wire:change="regionChanged" />

      <x-mary-select
          label="Province"
          :options="$provinces_options"
          option-value="id"
          option-label="label"
          placeholder="Select a province"
          placeholder-value=""
					hint="Select one, please."
          wire:model="province_id"
      />

      <x-mary-input label="City / Municipality Description" wire:model="label" id="label" />

      <x-mary-input label="Latitude (Location)" wire:model="latitude" id="latitude" />

      <x-mary-input label="Longitude (Location)" wire:model="longitude" id="longitude" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addCityMunicipalityModal = false"/>
          <x-mary-button 
                  label="Save Record" 
                  class="btn-primary" 
                  type="submit" 
                  spinner="save_city_municipality"
                  wire:target="save_city_municipality" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="editCityMunicipalityModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Edit LGU Type</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_city_municipality_record_changes" no-separator>

      <x-mary-input type="hidden" wire:model="city_municipality_id" id="city_municipality_id" />

      <x-mary-select
						label="Country"
						:options="$this->load_country_options"
						option-value="id"
						option-label="label"
						placeholder="Select a country"
						placeholder-value=""
						hint="Select one, please."
						wire:model="edit_country_id" />

      <div class="flex items-center gap-2">
        <x-mary-select
              label="LGU Type"
              :options="$this->load_lgu_type_options"
              option-value="id"
              option-label="label"
              placeholder="Select a LGU Type"
              placeholder-value=""
              hint="Select one, please."
              wire:model="edit_lgu_type_id" />

        <button 
                    type="button"
                    class="btn btn-sm btn-primary" 
                    wire:click="$set('addLGUTypeModal', true)"
                    >+ Add</button>
      </div>

      <x-mary-select
						label="Region"
						:options="$this->load_region_options"
						option-value="id"
						option-label="abbreviation"
						placeholder="Select a region"
						placeholder-value=""
						hint="Select one, please."
						wire:model="edit_region_id"
            wire:change="edit_regionChanged" />

      <x-mary-select
          label="Province"
          :options="$provinces_options"
          option-value="id"
          option-label="label"
          placeholder="Select a province"
          placeholder-value=""
					hint="Select one, please."
          wire:model="edit_province_id"
      />

      <x-mary-input label="City / Municipality Description" wire:model="edit_label" id="edit_label" />

      <x-mary-input label="Latitude (Location)" wire:model="edit_latitude" id="edit_latitude" />

      <x-mary-input label="Longitude (Location)" wire:model="edit_longitude" id="edit_longitude" />

      <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.editCityMunicipalityModal = false"/>
        <x-mary-button 
                    label="Save Record" 
                    class="btn-primary" 
                    type="submit" 
                    spinner="save_city_municipality_record_changes"
                    wire:target="save_city_municipality_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="updateCityMunicipalityStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updateCityMunicipalityStatusModal = false" />
        <x-mary-button 
              label="Confirm" 
              class="btn-primary" 
              spinner="delete" 
              wire:click="update_city_municipality_status({{ $city_municipality_id }}, {{ $statuscode }})" 
              wire:target="update_city_municipality_status" 
              />
    </x-slot:actions>

  </x-mary-modal>


  <x-mary-modal wire:model="addLGUTypeModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add LGU Type</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_lgu_type" no-separator>

      <x-mary-input label="Abbreviation" wire:model="lgu_type_abbreviation" id="lgu_type_abbreviation" />

      <x-mary-input label="Description" wire:model="lgu_type_label" id="lgu_type_label" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addLGUTypeModal = false"/>
          <x-mary-button 
                  label="Save Record" 
                  class="btn-primary" 
                  type="submit" 
                  spinner="save_lgu_type"
                  wire:target="save_lgu_type" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <!-- 
    Loader goes here 
  -->
  <x-livewire-loader target="city_municipality_lst" message="Please wait while the system loads all citizenship records for you..." />

  <x-livewire-loader target="save_city_municipality,save_city_municipality_record_changes" message="Saving... please wait..." />

  <x-livewire-loader target="openEditCityMunicipalityModal" message="Please wait while the system retrieves the record for you..." />

  <x-livewire-loader target="update_city_municipality_status" message="Updating record status... please wait..." />

</div>