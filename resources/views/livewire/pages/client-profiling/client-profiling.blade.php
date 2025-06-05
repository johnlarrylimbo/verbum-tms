<div>
  <x-mary-header title="::..Client Profiling">
      {{-- <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Roles..."  wire:model.live="search"/>
      </x-slot:middle> --}}
      {{-- <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addRoleModal = true" />
      </x-slot:actions> --}}
  </x-mary-header>


  @if ($showSuccessMessage)
    <div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => { show = false; @this.set('showSuccessMessage', false) }, 3000)"
    x-transition
    class="fixed top-4 right-4 z-50">
      <x-mary-alert icon="s-check-circle" class="alert-success text-white">
          Record updated successfully!
      </x-mary-alert>
    </div>
  @endif

  @if ($showAddSuccessMessage)
    <div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => { show = false; @this.set('showAddSuccessMessage', false) }, 3000)"
    x-transition
    class="fixed top-4 right-4 z-50">
      <x-mary-alert icon="s-check-circle" class="alert-success text-white">
          Record added successfully!
      </x-mary-alert>
    </div>
  @endif

  @if ($showAddErrorMessage)
    <div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => { show = false; @this.set('showAddErrorMessage', false) }, 3000)"
    x-transition
    class="fixed top-4 right-4 z-50">
      <x-mary-alert icon="c-x-circle" class="bg-danger text-white">
        Failed to add new record. Record already exists in our database.
      </x-mary-alert>
    </div>
  @endif

  @if ($showErrorMessage)
    <div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => { show = false; @this.set('showErrorMessage', false) }, 3000)"
    x-transition
    class="fixed top-4 right-4 z-50">
      <x-mary-alert icon="c-x-circle" class="bg-danger text-white">
        Failed to update record. Record does not exists.
      </x-mary-alert>
    </div>
  @endif

  <x-mary-card>

    <x-mary-form wire:submit.prevent="save_role" no-separator>

      <x-mary-card title="Personal Profile" subtitle="Client personal profile information" separator progress-indicator>
          {{-- <x-mary-button label="Save" wire:click="save" /> --}}

        <div class="p-4 bg-white rounded-lg shadow-md">
          <div class="flex flex-col md:flex-row md:items-end md:gap-4">
            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Client Name" wire:model="client_name" id="client_name" />
            </div>&nbsp;

            <div class="w-full md:w-1/2 md:ml-4 mb-1" style="margin-bottom: 4px; width: 50% !important;">
              <x-mary-datetime label="Birthdate" wire:model="birthdate" id="birthdate" />
            </div>
          </div>

          <div class="flex flex-col md:flex-row md:items-end md:gap-4">
            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Room/Floor/Unit/Bldg" wire:model="client_name" id="client_name" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Lot / Block" wire:model="client_name" id="client_name" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Street" wire:model="client_name" id="client_name" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Subdivision" wire:model="client_name" id="client_name" />
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
                placeholder-value=""
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
                placeholder-value=""
                hint="Select one, please."
                wire:model="region_id"
                wire:change="regionChanged" />
            </div>&nbsp;
            
          </div>
        </div>

      </x-mary-card>

      <x-mary-card title="Spouse Information" subtitle="Client spouse profile information" separator progress-indicator>
          <x-mary-button label="Save" wire:click="save" />
      </x-mary-card>

    </x-mary-form>

    {{-- <div class="my-4">
      {{ $this->role_lst->links() }}
    </div> --}}
    {{-- <br /> --}}
  
    <!-- Wrap table in responsive container -->
    {{-- <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="5%">#</th>
            <th class="text-center bg-primary text-white" width="15%">Abbreviation</th>
            <th class="text-center bg-primary text-white" width="30%">Role Description</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->role_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="5">No role record(s) found.</td>
            </tr>
          @else
            @foreach ($this->role_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="text-center vertical-align-top">{{ $result->row_num }}</td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">{{ $result->abbreviation }}</td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">{{ $result->label }}</td>
                <td class="text-center vertical-align-top">
                  @if($result->statuscode == 1)
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-green-600 text-white" />
                  @else
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-red-600 text-white" />
                  @endif
                </td>
                <td class="text-center vertical-align-top">
                  <x-mary-button icon="o-pencil-square" 
                                  wire:click="openEditRoleModal({{ $result->role_id }})" 
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdateRoleStatusModal({{ $result->role_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdateRoleStatusModal({{ $result->role_id }},{{ $result->statuscode }})"
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
    </div> --}}

    {{-- <div class="my-4">
      {{ $this->role_lst->links() }}
    </div> --}}

  </x-mary-card>


  {{-- <x-mary-modal wire:model="addRoleModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add Religion</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_role" no-separator>

      <x-mary-input label="Abbreviation" wire:model="abbreviation" id="abbreviation" />

      <x-mary-input label="Description" wire:model="label" id="label" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addRoleModal = false"/>
          <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save_role" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal> --}}

  {{-- <x-mary-modal wire:model="editRoleModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Edit Role</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_role_record_changes" no-separator>

      <x-mary-input type="hidden" wire:model="role_id" id="role_id" />

      <x-mary-input label="Abbreviation" wire:model="edit_abbreviation" id="edit_abbreviation" />

      <x-mary-input label="Description" wire:model="edit_label" id="edit_label" />

      <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.editRoleModal = false"/>
        <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save_role_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>   --}}

  {{-- <x-mary-modal wire:model="updateRoleStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updateRoleStatusModal = false" />
        <x-mary-button label="Confirm" class="btn-primary" spinner="delete" wire:click="update_role_status({{ $role_id }}, {{ $statuscode }})"  />
    </x-slot:actions>

  </x-mary-modal> --}}


</div>