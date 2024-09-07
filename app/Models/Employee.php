<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_employee', 'employee_id', 'organization_id');
    }
}
