<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';

    // IDのカラム名
    protected $primaryKey = 'id';

    // 自動インクリメントを使用しない
    public $incrementing = false;

    // IDの型
    protected $keyType = 'string';

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'organization_employee', 'organization_id', 'employee_id');
    }
}
