<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';

    // 多対多のリレーション (中間テーブルを使用)
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'organization_employee', 'organization_id', 'employee_id');
    }
}
