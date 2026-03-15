<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Trainee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'changes',
        'read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ ADD THIS METHOD
    public function trainee()
    {
        return $this->belongsTo(Trainee::class, 'model_id');
    }

    public function employmentLog()
    {
        return $this->belongsTo(EmploymentLog::class, 'model_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'model_id');
    }
}
