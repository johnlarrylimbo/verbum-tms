<div>
  <x-mary-header title="::.. Contract Monitoring Board"></x-mary-header>


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


  <x-mary-card class="flex flex-col lg:flex-row gap-6">

    <div class="p-6 max-w-7xl mx-auto">
        {{-- <h1 class="text-3xl font-bold mb-6 text-center">📋 Task Monitoring Board</h1> --}}

        {{-- Add Task Form --}}
        {{-- <form wire:submit.prevent="addTask" class="flex flex-col md:flex-row items-stretch md:items-center gap-4 mb-8">
            <input wire:model.defer="newTask"
                  type="text"
                  class="flex-1 px-4 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                  placeholder="Enter task title" />

            <select wire:model="newTaskStatus"
                    class="px-4 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="todo">To Do</option>
                <option value="doing">Doing</option>
                <option value="done">Done</option>
            </select>

            <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                ➕ Add
            </button>
        </form> --}}

        {{-- Task Columns --}}
        <div class="flex lg:flex-row gap-6">
            {{-- TO DO --}}
            <div class="w-full lg:w-1/3 bg-gray-100 p-4 rounded shadow">
                <h2 class="text-xl font-semibold mb-4 text-center">📝 On Duration</h2>
                <div class="space-y-2">
                        <div class="bg-white p-4 rounded shadow flex justify-between items-center">
                            <span>Sample Title</span>
                            <div class="flex gap-2">
                                <button wire:click="moveTask(1, 'doing')" class="text-yellow-600 hover:text-yellow-800">➡</button>
                                <button wire:click="deleteTask(1)" class="text-red-600 hover:text-red-800">🗑</button>
                            </div>
                        </div>
                </div>
            </div>
            

            {{-- DOING --}}
            <div class="w-full lg:w-1/3 custom-bg-red p-4 rounded shadow">
                <h2 class="text-xl font-semibold mb-4 text-center">⏳ Expired</h2>
                <div class="space-y-2">
                        <div class="bg-white p-4 rounded shadow flex justify-between items-center">
                            <span>Sample Title</span>
                            <div class="flex gap-2">
                                <button wire:click="moveTask(1, 'todo')" class="text-blue-600 hover:text-blue-800">⬅</button>
                                <button wire:click="moveTask(1, 'done')" class="text-green-600 hover:text-green-800">➡</button>
                                <button wire:click="deleteTask(1)" class="text-red-600 hover:text-red-800">🗑</button>
                            </div>
                        </div>
                </div>
            </div>

            {{-- DONE --}}
            <div class="w-full lg:w-1/3 custom-bg-green p-4 rounded shadow">
                <h2 class="text-xl font-semibold mb-4 text-center">✅ Renewed</h2>
                <div class="space-y-2">
                        <div class="bg-white p-4 rounded shadow flex justify-between items-center">
                            <span class="line-through text-gray-600">Sample Title</span>
                            <div class="flex gap-2">
                                <button wire:click="moveTask(1, 'doing')" class="text-yellow-600 hover:text-yellow-800">⬅</button>
                                <button wire:click="deleteTask(1)" class="text-red-600 hover:text-red-800">🗑</button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

  </x-mary-card>

</div>