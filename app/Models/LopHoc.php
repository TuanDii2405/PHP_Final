<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LopHoc extends Model
{
    protected $table = 'Lop_hoc';
    protected $primaryKey = 'ID_LopHoc';
    public $timestamps = false;
    protected $guarded = [];
}
