<div x-data="{ init: false }" x-init="if (!init) { init = true; $wire.congregation_lst() }">
  <x-mary-header title="SystemLib :: Congregation Directory">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Congregation..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addCongregationModal = true" />
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
      {{ $this->congregation_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="8%">#</th>
            <th class="text-center bg-primary text-white" width="15%">Abbreviation</th>
            <th class="text-center bg-primary text-white" width="45%">Description</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->congregation_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="5">No congregation record(s) found.</td>
            </tr>
          @else
            @foreach ($this->congregation_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="align-center vertical-align-top">{{ $result->row_num }}</td>
                <td class="align-left vertical-align-top" style="word-break: break-word;">{{ $result->abbreviation }}</td>
                <td class="align-left vertical-align-top" style="word-break: break-word;">{{ $result->congregation_label }}</td>
                <td class="text-center vertical-align-top">
                  @if($result->statuscode == 1)
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-green-600 text-white" />
                  @else
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-red-600 text-white" />
                  @endif
                </td>
                <td class="text-center vertical-align-top">
                  <x-mary-button icon="o-pencil-square" 
                                  wire:click="openEditCongregationModal({{ $result->congregation_id }})"
                                  wire:target="openEditCongregationModal" 
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdateCongregationStatusModal({{ $result->congregation_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdateCongregationStatusModal({{ $result->congregation_id }},{{ $result->statuscode }})"
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
      {{ $this->congregation_lst->links() }}
    </div>

  </x-mary-card>


  <x-mary-modal wire:model="addCongregationModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add Congregation</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_congregation" no-separator>

      <x-mary-input label="Abbreviation" wire:model="abbreviation" id="abbreviation" />

      <x-mary-input label="Description" wire:model="description" id="description" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addCongregationModal = false"/>
          <x-mary-button 
                label="Save Record" 
                class="btn-primary" 
                type="submit" 
                spinner="save_congregation"
                wire:target="save_congregation" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="editCongregationModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Edit Congregation</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_congregation_record_changes" no-separator>

      <x-mary-input type="hidden" wire:model="congregation_id" id="congregation_id" />

      <x-mary-input label="Abbreviation" wire:model="edit_abbreviation" id="edit_abbreviation" />

      <x-mary-input label="Description" wire:model="edit_description" id="edit_description" />

      <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.editCongregationModal = false"/>
        <x-mary-button 
              label="Save Record" 
              class="btn-primary" 
              type="submit" 
              spinner="save_congregation_record_changes"
              wire:target="save_congregation_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="updateCongregationStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updateCongregationStatusModal = false" />
        <x-mary-button 
              label="Confirm" 
              class="btn-primary" 
              spinner="delete" 
              wire:click="update_congregation_status({{ $congregation_id }}, {{ $statuscode }})"
              wire:target="update_congregation_status"  />
    </x-slot:actions>

  </x-mary-modal>

  <!-- 
    Loader goes here 
  -->
  <x-livewire-loader target="congregation_lst" message="Please wait while the system loads all congregation records for you..." />

  <x-livewire-loader target="save_congregation,save_congregation_record_changes" message="Saving... please wait..." />

  <x-livewire-loader target="openEditCongregationModal" message="Please wait while the system retrieves the record for you..." />

  <x-livewire-loader target="update_congregation_status" message="Updating record status... please wait..." />

</div>