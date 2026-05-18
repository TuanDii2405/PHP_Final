<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeThi extends Model
{
    protected $table = 'De_Thi';
    protected $primaryKey = 'ID_MaDeThi';
    public $timestamps = false;
    protected $guarded = [];
}
