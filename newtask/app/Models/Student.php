<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'full_name','user_id','isActive'
    ];


    protected $primaryKey = 'user_id'; 


    public static function getStudentById($id)
    {
        return self::find($id);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subjects()
    {
    return $this->belongsToMany(Subject::class, 'subject_user', 'user_id', 'subject_id')->withPivot('mark');

    }
}
