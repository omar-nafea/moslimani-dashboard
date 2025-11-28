<x-filament-panels::page>
  <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
    @foreach($this->getStats() as $stat)
      <x-filament::card>
        <div class="flex items-center gap-x-4">
          <div class="flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
              {{ $stat->getLabel() }}
            </p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
              {{ $stat->getValue() }}
            </p>
            @if($stat->getDescription())
              <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $stat->getDescription() }}
              </p>
            @endif
          </div>
        </div>
      </x-filament::card>
    @endforeach
  </div>
</x-filament-panels::page>