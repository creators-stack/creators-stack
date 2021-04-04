<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class Logs extends Component
{
    public string $file = '';
    public array $files;
    public ?string $logs = null;

    protected $queryString = [
        'file' => ['except' => ''],
    ];

    public function mount()
    {
        $files = collect(Storage::disk('logs')->files())
            ->filter(fn (string $file) => Str::endsWith($file, '.log'))
            ->sortDesc();

        if ($files->isNotEmpty()) {
            if (is_null($this->file)) {
                $this->file = $files->first(fn (string $file) => str_contains($file, 'laravel'));
            }
        }

        $this->files = array_combine($files->toArray(), $files->toArray());
    }

    public function render()
    {
        $this->feedLogs();
        $this->emitSelf('rendering');

        return view('livewire.logs');
    }

    protected function feedLogs()
    {
        if (in_array($this->file, $this->files)) {
            $this->logs = Storage::disk('logs')->get($this->file);
        } else {
            $this->logs = null;
        }
    }
}
