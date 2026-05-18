<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KyThi extends Model
{
    protected $table = 'Ky_thi';
    protected $primaryKey = 'ID_KyThi';
    public $timestamps = false;
    protected $guarded = [];
}
