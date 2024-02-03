<?php

// Commoninformation.php (model)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commoninformation extends Model
{
    protected $primaryKey = 'CommonInfoID'; // Specify the primary key column

    protected $guarded = [
        'CommonInfoID'
    ];

    // Define the relationship with Checksheets
    public function checksheet()
    {
        return $this->hasMany(Checksheet::class, 'CommonInfoID', 'CommonInfoID');
    }
}
    
