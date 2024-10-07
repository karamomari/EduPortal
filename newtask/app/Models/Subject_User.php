<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject_User extends Model
{
    use HasFactory;
    
    protected $table = 'subject_user';
    
    protected $fillable = [
        'subject_id','user_id','mark',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
}
