<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextValue extends Model
{
    use HasFactory;

    protected $fillable = ['field_value_id', 'value'];

    public function fieldValue()
    {
        return $this->belongsTo(FieldValue::class);
    }
}
