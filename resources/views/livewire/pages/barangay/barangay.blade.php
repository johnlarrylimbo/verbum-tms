<div>
  <x-mary-header title="SystemLib :: Barangay">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Barangay..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" />
      </x-slot:actions>
  </x-mary-header>

  <x-mary-card>

  <div class="my-4">
    {{ $this->barangay_lst->links() }}
  </div>
  <br />
  <!-- Wrap table in responsive container -->
  <div style="overflow-x: auto; width: 100%;">
    <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
      <thead>
        <tr class="fs-14 pink h-2rem">
          <th class="text-center bg-primary text-white" width="8%">#</th>
          <th class="text-center bg-primary text-white" width="20%">Description</th>
          <th class="text-center bg-primary text-white" width="20%">City / Municipality</th>
          <th class="text-center bg-primary text-white" width="20%">Province</th>
          <th class="text-center bg-primary text-white">Status</th>
          <th class="text-center bg-primary text-white">Manage</th>
        </tr>
      </thead>
      <tbody>
        @if(count($this->barangay_lst) == 0)
          <tr class="fs-13 border-btm content-tr">
            <td class="align-center" colspan="6">No barangay record(s) found.</td>
          </tr>
        @else
          @foreach ($this->barangay_lst as $result)
            <tr class="fs-13 border-btm content-tr">
              <td class="align-center vertical-align-top">{{ $result->row_num }}</td>
              <td class="align-left vertical-align-top" style="word-break: break-word;">{{ $result->label }}</td>
              <td class="align-left vertical-align-top" style="word-break: break-word;">{{ $result->city_unicipality_label }}</td>
              <td class="align-left vertical-align-top" style="word-break: break-word;">{{ $result->province_label }}</td>
              <td class="text-center vertical-align-top">
                @if($result->statuscode == 1)
                  <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-green-600 text-white" />
                @else
                  <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-red-600 text-white" />
                @endif
              </td>
              <td class="text-center vertical-align-top">
                <x-mary-button icon="o-pencil-square" 
                                wire:click="openEditClearanceAreaModal({{ $result->barangay_id }})" 
                                spinner 
                                class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                <x-mary-button icon="o-trash"
                                wire:click="openDeleteClearanceAreaModal({{ $result->barangay_id }})"
                                class="bg-red-600 text-white btn-sm align-center"
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
    {{ $this->barangay_lst->links() }}
  </div>

</x-mary-card>


</div>