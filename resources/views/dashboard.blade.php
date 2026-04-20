<x-layouts::app :title="__('Home')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-5">

            {{-- TOTAL --}}
            <div class="relative overflow-hidden rounded-xl border p-4">
                <div class="text-sm text-neutral-500">Total entries</div>
                <div class="text-2xl font-bold">{{ $totalEntries }}</div>
            </div>

            {{-- HAPPY --}}
            <div class="relative overflow-hidden rounded-xl border p-4">
                <div class="text-sm">😊 Happy</div>
                <div class="text-xl font-bold">{{ $moodStats['happy'] ?? 0 }}</div>
                <div class="text-xs text-neutral-500">
                    {{ $moodPercentages['happy'] ?? 0 }}%
                </div>
            </div>

            {{-- SAD --}}
            <div class="relative overflow-hidden rounded-xl border p-4">
                <div class="text-sm">😔 Sad</div>
                <div class="text-xl font-bold">{{ $moodStats['sad'] ?? 0 }}</div>
                <div class="text-xs text-neutral-500">
                    {{ $moodPercentages['sad'] ?? 0 }}%
                </div>
            </div>

            {{-- ANGRY --}}
            <div class="relative overflow-hidden rounded-xl border p-4">
                <div class="text-sm">😠 Angry</div>
                <div class="text-xl font-bold">{{ $moodStats['angry'] ?? 0 }}</div>
                <div class="text-xs text-neutral-500">
                    {{ $moodPercentages['angry'] ?? 0 }}%
                </div>
            </div>

            {{-- Calm --}}
            <div class="relative overflow-hidden rounded-xl border p-4">
                <div class="text-sm">😌 Calm</div>
                <div class="text-xl font-bold">{{ $moodStats['calm'] ?? 0 }}</div>
                <div class="text-xs text-neutral-500">
                    {{ $moodPercentages['calm'] ?? 0 }}%
                </div>
            </div>

            {{-- Excited --}}
            <div class="relative overflow-hidden rounded-xl border p-4">
                <div class="text-sm">🤩 Excited</div>
                <div class="text-xl font-bold">{{ $moodStats['excited'] ?? 0 }}</div>
                <div class="text-xs text-neutral-500">
                    {{ $moodPercentages['excited'] ?? 0 }}%
                </div>
            </div>


        </div>
        <div class="h-full overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <div class="flex w-full flex-col gap-10 lg:flex-row lg:items-center justify-between">
                <div class="space-y-3">

                    <h2 class="text-lg font-semibold">
                        Mood analysis
                    </h2>

                    <p class="text-neutral-700 dark:text-neutral-300">
                        {{ $summary }}
                    </p>

                    @if($dominantMood)
                        <div class="text-sm text-neutral-500">
                            Dominant mood:
                            <span @class([
                                'font-medium',
                                'text-green-500' => $dominantMood === 'happy',
                                'text-blue-500' => $dominantMood === 'sad',
                                'text-red-500' => $dominantMood === 'angry',
                                'text-violet-500' => $dominantMood === 'calm',
                                'text-orange-500' => $dominantMood === 'excited',
                            ])>
                                {{ ucfirst($dominantMood) }}
                            </span>
                        </div>
                    @endif
                </div>
                <!-- MINI CHART -->
                <div class="flex items-end gap-2 h-32 justify-center lg:justify-start">
                    <div class="flex flex-col items-center justify-end h-full">
                        <div class="w-24 bg-green-400 rounded-sm transition-all duration-500"
                            style="height: {{ min(100, max(0, $moodPercentages['happy'])) }}%">
                        </div>
                        <span class="mt-2 text-xs">😄 Happy</span>
                    </div>
                    <div class="flex flex-col items-center justify-end h-full">
                        <div class="w-24 bg-yellow-400 rounded-sm transition-all duration-500"
                            style="height: {{ min(100, max(0, $moodPercentages['sad'])) }}%">
                        </div>
                        <span class="mt-2 text-xs">😔 Sad</span>
                    </div>
                    <div class="flex flex-col items-center justify-end h-full">
                        <div class="w-24 bg-red-400 rounded-sm transition-all duration-500"
                            style="height: {{ min(100, max(0, $moodPercentages['angry'])) }}%">
                        </div>
                        <span class="mt-2 text-xs">😠 Angry</span>
                    </div>
                    <div class="flex flex-col items-center justify-end h-full">
                        <div class="w-24 bg-blue-400 rounded-sm transition-all duration-500"
                            style="height: {{ min(100, max(0, $moodPercentages['calm'])) }}%">
                        </div>
                        <span class="mt-2 text-xs">😌 Calm</span>
                    </div>
                    <div class="flex flex-col items-center justify-end h-full">
                        <div class="w-24 bg-purple-400 rounded-sm transition-all duration-500"
                            style="height: {{ min(100, max(0, $moodPercentages['excited'])) }}%">
                        </div>
                        <span class="mt-2 text-xs">🤩 Excited</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>