<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name','admin_id'
    ];
     

    protected $primaryKey = 'admin_id'; 




    public static function getStudentById($id)
    {
        return self::find($id);
    }
}
