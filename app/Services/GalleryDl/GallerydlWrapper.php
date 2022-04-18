<?php

namespace App\Services\GalleryDl;

use Symfony\Component\Process\Process;

class GallerydlWrapper
{
    /** @var array<string> $options */
    protected array $options;
    protected ?string $url = null;

    public function __construct(protected ?string $pwd = null)
    {
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function destination(string $destination): self
    {
        $this->options[] = '--directory';
        $this->options[] = $destination;

        return $this;
    }

    public function extractorInfo(): self
    {
        $this->options[] = '--extractor-info';

        return $this;
    }

    public function exec(string $command): self
    {
        $this->options[] = '--exec';
        $this->options[] = $command;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function debug(): array
    {
        return [$this->url, ...$this->options];
    }

    public function run(): ProcessResponse
    {
        $process = new Process(['gallery-dl', $this->url, ...$this->options], $this->pwd, null, null, null);

        $process->run();

        return new ProcessResponse($process->getExitCode(), $process->getOutput(), $process->getErrorOutput());
    }
}
