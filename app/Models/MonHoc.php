<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonHoc extends Model
{
    protected $table = 'Mon_Hoc';
    protected $primaryKey = 'ID_MonHoc';
    public $timestamps = false;
    protected $guarded = [];
}
