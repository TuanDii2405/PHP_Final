<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiemSo extends Model
{
    protected $table = 'Diem_so';
    protected $primaryKey = 'ID_DiemSo';
    public $timestamps = false;
    protected $guarded = [];
}
