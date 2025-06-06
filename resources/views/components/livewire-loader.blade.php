<div
    wire:loading
    wire:target="{{ $target }}"
    class="fixed inset-0 flex items-center justify-center bg-white/10 backdrop-blur-sm z-50 rounded-lg"
>
    <div class="text-center">
        <x-mary-loading class="progress-primary w-10 h-10" style="margin-top: 400px;" />
        <p class="mt-2 text-gray-700 font-medium">{{ $message }}</p>
    </div>
</div>