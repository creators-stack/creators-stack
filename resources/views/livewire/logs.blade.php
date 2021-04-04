<div class="py-12 sm:mx-5 md:mx=10 lg:mx-20">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-lg p-5">
            <h1 class="text-2xl font-medium text-gray-900">
                {{ __('Logs') }}
            </h1>
            <div class="mt-5">
                <div>
                    <x-form.select :options="$files" wire:model="file" />
                </div>
                <div class="mt-4">
                    @if (empty($logs))
                        <p>No logs to render</p>
                    @else
                        <pre><code class="text-xs language-php">{{ $logs }}</code></pre>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('livewire:load',  () => {
        window.hljs.highlightAll();

        @this.on('rendering', () => {
            window.hljs.highlightAll();
        })
    });
</script>
