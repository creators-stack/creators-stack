<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class View
 *
 * @property int id
 * @property int file_id
 * @property string ip
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property File file
 */
class View extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
