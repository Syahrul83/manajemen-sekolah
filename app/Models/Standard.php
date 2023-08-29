<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Standard extends Model
{
    use HasFactory;

    public function student(){
        return $this->hasMany(Student::class);
    }

    protected function nilai(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>   $attributes['id'] +   $attributes['class_number'],

        );
    }


}
