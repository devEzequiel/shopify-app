<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1, $email)
 * @property mixed email
 * @property mixed code
 */
class ConfirmationCode extends Model
{
    use HasFactory;

    protected $fillable =[
        'email',
        'code',
    ];
}
