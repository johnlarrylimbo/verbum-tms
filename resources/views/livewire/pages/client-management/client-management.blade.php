<div x-data="{ init: false }" x-init="if (!init) { init = true; $wire.client_profile_lst() }">
  <x-mary-header title="::.. Client List">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Category..."  wire:model.live="search"/>
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
                                  wire:click="openEditClientCategoryModal({{ $result->client_id }})" 
                                  wire:target="openEditClientCategoryModal"
                                  spinner 
                                  class="bg-primary text-white btn-sm align-center" />
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdateClientCategoryStatusModal({{ $result->client_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdateClientCategoryStatusModal({{ $result->client_id }},{{ $result->statuscode }})"
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
      {{ $this->client_profile_lst->links() }}
    </div>

  </x-mary-card>


  <!-- 
    Loader goes here 
  -->
  <x-livewire-loader target="client_profile_lst" message="Please wait while the system loads all client profile records for you..." />

  {{-- <x-livewire-loader target="save_client_category,save_client_category_record_changes" message="Saving... please wait..." /> --}}

  <x-livewire-loader target="openEditClientProfileByIdWindow" message="Please wait while the system retrieves the record for you..." />

  {{-- <x-livewire-loader target="update_client_category_status" message="Updating record status... please wait..." /> --}}

</div>