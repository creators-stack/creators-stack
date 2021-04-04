@props(['label', 'path', 'help'])

<div class="my-4">
    <x-jet-label :for="$attributes->get('id')">{{ $label }}</x-jet-label>
    <div class="mt-1 relative" x-data="initAutocomplete()">
        <x-jet-input {{ $attributes->merge([
            'autocomplete' => 'off',
            'x-model' => $attributes->wire('model')->value(),
            'x-on:keydown.tab' => 'applySuggestion($event)',
            'x-on:keydown' => 'updateSuggestions($event)',
        ]) }} />
        <template x-if="suggestion">
            <a x-text="suggestion" class="absolute top-0 right-2 flex pointer-events-none mt-2 ml-2 text-gray-400"></a>
        </template>
    </div>
    <x-jet-input-error :for="$attributes->wire('model')->value()"/>
    @if (!empty($help))
        <x-help>{{ $help }}</x-help>
    @endif
</div>
<script>
    function initAutocomplete() {
        return {
            path: @entangle('path').defer,
            suggestions: @entangle('suggestions'),
            suggestion: null,
            applySuggestion(event) {
                if (!this.suggestion || this.suggestion === this.path) {
                    return;
                }

                event.preventDefault();

                if (!this.path.includes('/')) {
                    this.path = '';
                }

                this.path = this.beforeLast(this.path, '/') + this.suggestion;
                this.suggestion = null;
                @this.feedSuggestions();
            },
            updateSuggestions(event) {
                last_path = this.path || '';

                this.$nextTick(() => {
                    if (last_path.split('/').length !== this.path.split('/').length) {
                        @this.feedSuggestions().then(() => {
                            this.suggest(event);
                        });
                    } else {
                        this.suggest(event);
                    }
                })
            },
            suggest(event) {
                path = this.afterLast(this.path, '/').toLowerCase();

                if (!path || event.key === 'Tab') {
                    this.suggestion = null;
                    return;
                }

                suggestions = JSON.parse(JSON.stringify(this.suggestions));
                suggestion = suggestions.find((suggestion) => {
                    return suggestion.toLowerCase().includes(path);
                });

                if (suggestion !== this.path) {
                    this.suggestion = suggestion;
                }
            },
            beforeLast(str, chr) {
                return str.includes(chr) ? str.substring(0, str.lastIndexOf(chr)) : str;
            },
            afterLast(str, chr) {
                return str.includes(chr) ? str.substring(str.lastIndexOf(chr) + 1) : str;
            }
        };
    }
</script>
