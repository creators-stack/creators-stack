<?php

namespace App\Services\GalleryDl;

class ProcessResponse
{
    public function __construct(private int $exitCode, private ?string $stdout, private ?string $stderr)
    {
    }

    public function isSuccessful(): bool
    {
        return 0 === $this->exitCode;
    }

    public function exitCode(): int
    {
        return $this->exitCode;
    }

    public function stdout(): ?string
    {
        return $this->stdout;
    }

    public function stderr(): ?string
    {
        return $this->stderr;
    }
}
