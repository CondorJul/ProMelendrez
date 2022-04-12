<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    use HasFactory;
    protected $primaryKey = 'cardId';
    protected $table = 'cards';
    protected $fillable = [
        'cardName',
        'cardPhrases',
        'cardState'
    ];
}
