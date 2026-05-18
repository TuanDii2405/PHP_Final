<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhoiLop extends Model
{
    protected $table = 'Khoi_lop';
    protected $primaryKey = 'ID_KhoiLop';
    public $timestamps = false;
    protected $guarded = [];
}
