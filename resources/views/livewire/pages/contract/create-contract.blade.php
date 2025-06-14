<div>
  <x-mary-header title="::.. Create Contract">
      {{-- <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Contract..."  wire:model.live="search"/>
      </x-slot:middle> --}}
      {{-- <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addContractModal = true" />
      </x-slot:actions> --}}
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

    <x-mary-form wire:submit.prevent="save_contract" no-separator>

      <x-mary-card title="Contract Information" subtitle="Client contract information" separator progress-indicator>

        <div class="p-4 bg-white rounded-lg shadow-md">

          <div class="flex flex-col md:flex-row md:gap-4">
            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <!-- Contract No Input -->
              <x-mary-input label="Contract No." wire:model="contract_no_disabled" id="contract_no_disabled" disabled="disabled"/>
              <x-mary-input type="hidden" wire:model="contract_no" id="contract_no" />

              <!-- Generate Button -->
              <x-mary-button wire:click="generateContractNo" label="Generate Contract No" icon="c-hashtag" class="mt-2" />
            </div>

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Client Name"
                :options="$this->load_client_options"
                option-value="id"
                option-label="client_name"
                placeholder="Select a client"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="client_id" />
            </div>&nbsp;
          </div>
          <br />

          <div class="flex flex-col md:flex-row md:gap-4">
            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Contract Category"
                :options="$this->load_contract_category_options"
                option-value="id"
                option-label="label"
                placeholder="Select a contract category"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="contract_category_id" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Contract Type"
                :options="$this->load_contract_type_options"
                option-value="id"
                option-label="label"
                placeholder="Select a contract type"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="contract_type_id"
                wire:change="contractTypeChanged" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Contract Detail"
                :options="$contract_detail_options"
                option-value="id"
                option-label="label"
                placeholder="Select a contract detail"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="contract_detail_id" />
            </div>&nbsp;
             
          </div>
          <br />

          <div class="flex flex-col md:flex-row md:gap-4">
            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Contact Person" wire:model="contact_person" id="contact_person" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-input label="Contact Person Designation" wire:model="contact_person_designation" id="contact_person_designation" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px;">
              <x-mary-select
                label="Account Representative"
                :options="$this->load_employee_options"
                option-value="id"
                option-label="employee_name"
                placeholder="Select a account representative"
                placeholder-value="0"
                hint="Select one, please."
                wire:model="account_representative_id" />
            </div>&nbsp;

          </div>
          <br />

          <div class="flex flex-col md:flex-row md:gap-4">

            <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 50% !important;">
              <x-mary-datetime label="Contract Start" wire:model="contract_start" id="contract_start" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 50% !important;">
              <x-mary-datetime label="Contract End" wire:model="contract_end" id="contract_end" />
            </div>&nbsp;

            <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 50% !important;">
              {{-- <x-mary-input label="Contract Amount" wire:model="contract_amount" id="contract_amount" prefix="PHP" money /> --}}
              <x-mary-input 
                        prefix="PHP"
                        label="Amount" 
                        wire:model.lazy="contract_amount" 
                        type="text" 
                        inputmode="decimal" 
                        pattern="^\d+(\.\d{1,2})?$"
                    />
            </div>&nbsp;

          </div>
          <br />

          <div class="flex flex-col md:flex-row md:gap-4">

            <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 50% !important;">
              <x-mary-input label="Remarks" wire:model="remarks" id="remarks" />
            </div>&nbsp;

          </div>

        </div>

      </x-mary-card>

      <x-slot:actions>
          <x-mary-button label="Cancel" onclick="window.location.href='{{ url('contracts') }}'" />
          <x-mary-button 
                  label="Save Record" 
                  class="btn-primary" 
                  type="submit" 
                  spinner="save_contract"
                  wire:target="save_contract" />
      </x-slot:actions>

    </x-mary-form>

  </x-mary-card>

  <!-- 
    Loader goes here 
  -->
  <x-livewire-loader target="save_contract" message="Saving... please wait..." />

</div>