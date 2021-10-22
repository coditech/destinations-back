<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;
    protected $fillable = ['title','description','price','continent_id'];
    public function continent(){
        return $this->belongsTo(Continent::class);
    }
}
