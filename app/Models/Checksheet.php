<?php

// Checksheet.php (model)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checksheet extends Model
{
    protected $primaryKey = 'CommonInfoID'; 

    protected $guarded=[
        'id'
    ];

    // Define the relationship with CommonInfo
    public function commonInfo(): BelongsTo
    {
        return $this->belongsTo(Commoninformation::class, 'CommonInfoID', 'CommonInfoID');
    }
}

