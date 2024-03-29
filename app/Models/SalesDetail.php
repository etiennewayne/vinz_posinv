<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use HasFactory;
    

    protected $table = 'sales_details';
    protected $primaryKey = 'sales_detail_id';
    protected $fillable = [
        'sales_id', 'item_id', 'item_name',
        'qty', 'remarks', 'price'
    ];


    public function sales(){
        return $this->belongsTo(Sales::class, 'sales_id', 'sales_id');
    }

    public function item(){
        return $this->hasOne(Item::class, 'item_id', 'item_id');
    }

    public function serials(){
        return $this->hasMany(SalesItemSerial::class, 'sales_detail_id', 'sales_detail_id');
    }

    
}
