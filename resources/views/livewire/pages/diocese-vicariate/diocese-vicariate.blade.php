<div x-data="{ init: false }" x-init="if (!init) { init = true; $wire.vicariate_lst() }">
  <x-mary-header title="SystemLib :: Vicariate">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Vicariate..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addVicariateModal = true" />
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
      {{ $this->vicariate_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="5%">#</th>
            <th class="text-center bg-primary text-white" width="30%">Vicariate Name</th>
            <th class="text-center bg-primary text-white" width="30%">Diocese</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->vicariate_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="5">No vicariate record(s) found.</td>
            </tr>
          @else
            @foreach ($this->vicariate_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="align-top text-center">{{ $result->row_num }}</td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">{{ $result->label }} </td>
                <td class="text-center vertical-align-top" style="word-break: break-word;">{{ $result->diocese_name }}</td>
                <td class="text-center vertical-align-top">
                  @if($result->statuscode == 1)
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-green-600 text-white" />
                  @else
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-red-600 text-white" />
                  @endif
                </td>
                <td class="text-center vertical-align-top">
                  <x-mary-button icon="o-pencil-square" 
                                  wire:click="openEditVicariateModal({{ $result->vicariate_id }})" 
                                  wire:target="openEditVicariateModal"
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdateVicariateStatusModal({{ $result->vicariate_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdateVicariateStatusModal({{ $result->vicariate_id }},{{ $result->statuscode }})"
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
      {{ $this->vicariate_lst->links() }}
    </div>

  </x-mary-card>


  <x-mary-modal wire:model="addVicariateModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add Diocese Vicariate</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_vicariate" no-separator>

      <x-mary-input label="Vicariate Name" wire:model="label" id="label" />

      <x-mary-select
						label="Diocese"
						:options="$this->load_diocese_options"
						option-value="id"
						option-label="diocese_label"
						placeholder="Select a diocese"
						placeholder-value=""
						hint="Select one, please."
						wire:model="diocese_id" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addVicariateModal = false"/>
          <x-mary-button 
                label="Save Record" 
                class="btn-primary" 
                type="submit" 
                spinner="save_vicariate"
                wire:target="save_vicariate" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="editVicariateModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Edit Diocese Vicariate</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_vicariate_record_changes" no-separator>

      <x-mary-input type="hidden" wire:model="vicariate_id" id="vicariate_id" />

      <x-mary-input label="Vicariate Name" wire:model="edit_label" id="edit_label" />

      <x-mary-select
						label="Diocese"
						:options="$this->load_diocese_options"
						option-value="id"
						option-label="diocese_label"
						placeholder="Select a diocese"
						placeholder-value=""
						hint="Select one, please."
						wire:model="edit_diocese_id" />

      <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.editVicariateModal = false"/>
        <x-mary-button 
              label="Save Record" 
              class="btn-primary" 
              type="submit" 
              spinner="save_vicariate_record_changes"
              wire:target="save_vicariate_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="updateVicariateStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updateVicariateStatusModal = false" />
        <x-mary-button 
              label="Confirm" 
              class="btn-primary" 
              spinner="delete" 
              wire:click="update_vicariate_status({{ $vicariate_id }}, {{ $statuscode }})"
              wire:target="update_vicariate_status"  />
    </x-slot:actions>

  </x-mary-modal>


  <!-- 
    Loader goes here 
  -->
  <x-livewire-loader target="vicariate_lst" message="Please wait while the system loads all diocese vicariate records for you..." />

  <x-livewire-loader target="save_vicariate,save_vicariate_record_changes" message="Saving... please wait..." />

  <x-livewire-loader target="openEditVicariateModal" message="Please wait while the system retrieves the record for you..." />

  <x-livewire-loader target="update_vicariate_status" message="Updating record status... please wait..." />


</div>