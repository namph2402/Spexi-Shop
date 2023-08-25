<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportingNote extends Model
{
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(ImportingNoteDetail::class, 'note_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
