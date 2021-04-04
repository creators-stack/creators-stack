<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ContentPathAutocompletion
{
    public ?string $path = null;
    public array $suggestions = [];

    public function mountContentPathAutocompletion()
    {
        $this->feedSuggestions();
    }

    public function feedSuggestions()
    {
        $path = $this->path;

        if (! $path || ! str_contains($path, '/')) {
            $path = '';
        } else {
            $path = Str::trimSlashes(Str::beforeLast($this->path, '/'));
        }

        $this->suggestions = collect(Storage::disk('content')->directories($path))
            ->map(fn (string $dir) => '/'.$dir)
            ->map(fn (string $dir) => '/'.Str::afterLast($dir, '/').'/')
            ->toArray();
    }
}
