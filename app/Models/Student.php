<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }

public function guardians()
{
    return 	$this->belongsToMany(Guardian::class);
}

}
