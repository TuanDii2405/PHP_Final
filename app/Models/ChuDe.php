<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChuDe extends Model
{
    protected $table = 'Chu_De';
    protected $primaryKey = 'ID_ChuDe';
    public $timestamps = false;
    protected $guarded = [];
}
