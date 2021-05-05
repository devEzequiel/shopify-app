<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $table = 'wishlist';
    protected $fillable = [
        'costumer_id',
        'product_id'
    ];
    /**
     * @var string
     */
    private string  $costumer_id;
    /**
     * @var string
     */
    private string $product_id;

}
