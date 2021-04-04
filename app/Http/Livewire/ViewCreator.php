<?php

namespace App\Http\Livewire;

use App\Models\Creator;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ViewCreator extends Component
{
    public Creator $creator;
    public bool $opened = false;

    protected ?Batch $batch = null;

    public function render()
    {
        $this->setBatch();

        $this->creator->loadCount(['images', 'videos']);

        return view('livewire.view-creator', [
            'batch' => $this->batch,
        ]);
    }

    public function crawl()
    {
        $this->setBatch();

        if (! $this->batch || $this->batch->finished() === true) {
            Artisan::call('crawl:files', [
                'creator' => $this->creator->username,
            ]);
        }
    }

    public function cancelBatch()
    {
        $this->setBatch();

        if ($this->batch && $this->batch->cancelled() === false) {
            $this->batch->cancel();
            $this->batch = null;
        }
    }

    public function deleteCreator()
    {
        $this->creator->delete();

        $this->redirectRoute('creators');
    }

    protected function setBatch()
    {
        $batch = DB::table('job_batches')
            ->select('id')
            ->where('name', 'crawl_creator_'.$this->creator->username)
            ->orderByDesc('created_at')
            ->first();

        if ($batch) {
            $this->batch = Bus::findBatch($batch->id);
        }
    }
}
