<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    // IDのカラム名
    protected $primaryKey = 'id';

    // 自動インクリメントを使用しない
    public $incrementing = false;

    // IDの型
    protected $keyType = 'string';

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_employee', 'employee_id', 'organization_id');
    }
}
