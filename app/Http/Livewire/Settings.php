<?php

namespace App\Http\Livewire;

use App\Models\Settings as AppSettings;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Settings extends Component
{
    public AppSettings $settings;
    public string $image_extensions;
    public string $video_extensions;

    protected ?Batch $last_batch;

    protected $rules = [
        'settings.crawl_based_on_file_extension' => 'required|bool',
        'image_extensions' => 'required_if:settings.crawl_based_on_file_extension,1|string',
        'video_extensions' => 'required_if:settings.crawl_based_on_file_extension,1|string',
        'settings.generate_videos_preview' => 'nullable|bool',
        'settings.mute_videos_preview' => 'nullable|bool',
        'settings.videos_preview_parts_count' => 'required_if:settings.generate_videos_preview,1|integer|min:1',
        'settings.videos_preview_parts_length' => 'required_if:settings.generate_videos_preview,1|integer|min:200',
    ];

    public function mount()
    {
        $this->settings = App::make(AppSettings::class);

        $this->image_extensions = implode(',', $this->settings->image_extensions);
        $this->video_extensions = implode(',', $this->settings->video_extensions);
    }

    public function render()
    {
        $this->setLastBatch();

        return view('livewire.settings', [
            'last_batch' => $this->last_batch,
        ]);
    }

    public function saveSettings()
    {
        $this->validate();

        $this->settings->image_extensions = explode(',', $this->image_extensions);
        $this->settings->video_extensions = explode(',', $this->video_extensions);

        $this->settings->save();

        $this->emit('saved');
    }

    public function crawl()
    {
        $this->setLastBatch();

        if ($this->last_batch === null || $this->last_batch->finished() === true) {
            Artisan::call('crawl:files');
        }
    }

    public function cancelBatch()
    {
        $this->setLastBatch();

        if ($this->last_batch && $this->last_batch->cancelled() === false) {
            $this->last_batch->cancel();
        }
    }

    protected function setLastBatch()
    {
        $last_batch_id = DB::table('job_batches')
            ->whereNull('cancelled_at')
            ->orderByDesc('created_at')
            ->pluck('id')
            ->first();

        $this->last_batch = $last_batch_id ? Bus::findBatch($last_batch_id) : null;
    }
}
