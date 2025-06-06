<div x-data="{ init: false }" x-init="if (!init) { init = true; $wire.regional_center_lst() }">
  <x-mary-header title="SystemLib :: Regional Centers">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Centers..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addRegionalCenterModal = true" />
      </x-slot:actions>
  </x-mary-header>


  @if ($showMessageToast)
    <div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => { show = false; @this.set('showMessageToast', false) }, 3000)"
    x-transition
    class="fixed top-4 right-4 z-50">
      @if ($is_success)
        <x-mary-alert icon="s-check-circle" class="alert-success text-white">
            {{ $addMessage; }}
        </x-mary-alert>
      @else
        <x-mary-alert icon="c-x-circle" class="bg-danger text-white">
            {{ $addMessage; }}
        </x-mary-alert>
      @endif
    </div>
  @endif


  <x-mary-card>

    <div class="my-4">
      {{ $this->regional_center_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="5%">#</th>
            <th class="text-center bg-primary text-white" width="20%">Abbreviation</th>
            <th class="text-center bg-primary text-white" width="30%">Description</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->regional_center_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="5">No regional center record(s) found.</td>
            </tr>
          @else
            @foreach ($this->regional_center_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="align-top text-center">{{ $result->row_num }}</td>
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
                                  wire:click="openEditRegionalCenterModal({{ $result->regional_center_id }})" 
                                  wire:target="openEditRegionalCenterModal"
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdateRegionalCenterStatusModal({{ $result->regional_center_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdateRegionalCenterStatusModal({{ $result->regional_center_id }},{{ $result->statuscode }})"
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
      {{ $this->regional_center_lst->links() }}
    </div>

  </x-mary-card>


  <x-mary-modal wire:model="addRegionalCenterModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add Regional Center</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_regional_center" no-separator>

      <x-mary-input label="Abbreviation" wire:model="abbreviation" id="abbreviation" />

      <x-mary-input label="Description" wire:model="label" id="label" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addRegionalCenterModal = false"/>
          <x-mary-button 
                  label="Save Record" 
                  class="btn-primary" 
                  type="submit" 
                  spinner="save_regional_center"
                  wire:target="save_regional_center" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="editRegionalCenterModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Edit Regional Center</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_regional_center_record_changes" no-separator>

      <x-mary-input type="hidden" wire:model="regional_center_id" id="regional_center_id" />

      <x-mary-input label="Abbreviation" wire:model="edit_abbreviation" id="edit_abbreviation" />

      <x-mary-input label="Description" wire:model="edit_label" id="edit_label" />

      <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.editRegionalCenterModal = false"/>
        <x-mary-button 
                    label="Save Record" 
                    class="btn-primary" 
                    type="submit" 
                    spinner="save_regional_center_record_changes"
                    wire:target="save_regional_center_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="updateRegionalCenterStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updateRegionalCenterStatusModal = false" />
        <x-mary-button 
              label="Confirm" 
              class="btn-primary" 
              spinner="delete" 
              wire:click="update_regional_center_status({{ $regional_center_id }}, {{ $statuscode }})" 
              wire:target="update_regional_center_status" 
              />
    </x-slot:actions>

  </x-mary-modal>

  <!-- 
    Loader goes here 
  -->

  <!-- Loader using Blade Component for loading all record -->
	<div
			wire:loading
			wire:target="regional_center_lst"
			class="fixed inset-0 flex items-center justify-center bg-white/10 backdrop-blur-sm z-50 rounded-lg"
	>
			<div class="text-center">
					<x-mary-loading class="progress-primary w-10 h-10" style="margin-top: 400px;" />
					<p class="mt-2 text-gray-700 font-medium">Please wait while the system loads all regional center records for you...</p>
			</div>
	</div>

  <!-- Loader using Blade Component for saving new records -->
	<div
			wire:loading
			wire:target="save_regional_center"
			class="fixed inset-0 flex items-center justify-center bg-white/10 backdrop-blur-sm z-50 rounded-lg"
	>
			<div class="text-center">
					<x-mary-loading class="progress-primary w-10 h-10" style="margin-top: 400px;" />
					<p class="mt-2 text-gray-700 font-medium">Saving records changes... please wait...</p>
			</div>
	</div>

  <!-- Loader using Blade Component for retrieving records -->
	<div
			wire:loading
			wire:target="openEditRegionalCenterModal"
			class="fixed inset-0 flex items-center justify-center bg-white/10 backdrop-blur-sm z-50 rounded-lg"
	>
			<div class="text-center">
					<x-mary-loading class="progress-primary w-10 h-10" style="margin-top: 400px;" />
					<p class="mt-2 text-gray-700 font-medium">Please wait while the system retrieves the for you...</p>
			</div>
	</div>

  <!-- Loader using Blade Component for saving record changes -->
	<div
			wire:loading
			wire:target="save_regional_center_record_changes"
			class="fixed inset-0 flex items-center justify-center bg-white/10 backdrop-blur-sm z-50 rounded-lg"
	>
			<div class="text-center">
					<x-mary-loading class="progress-primary w-10 h-10" style="margin-top: 400px;" />
					<p class="mt-2 text-gray-700 font-medium">Saving records changes... please wait...</p>
			</div>
	</div>

  <!-- Loader using Blade Component for updated record status -->
	<div
			wire:loading
			wire:target="update_regional_center_status"
			class="fixed inset-0 flex items-center justify-center bg-white/10 backdrop-blur-sm z-50 rounded-lg"
	>
			<div class="text-center">
					<x-mary-loading class="progress-primary w-10 h-10" style="margin-top: 400px;" />
					<p class="mt-2 text-gray-700 font-medium">Updating record status... please wait...</p>
			</div>
	</div>


</div>