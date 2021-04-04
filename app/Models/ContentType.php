<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class ContentType.
 *
 * @property int id
 * @property string name
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class ContentType extends Model
{
    public const IMAGE = 1;
    public const VIDEO = 2;
    public $fillable = [
        'id',
        'name',
    ];
}
