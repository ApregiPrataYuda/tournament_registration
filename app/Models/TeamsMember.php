<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class TeamsMember extends Model
{
     use HasFactory;
     use SoftDeletes;
    protected $table = 'team_members';
    protected $primaryKey = 'member_id';
    public $incrementing = true;
    public $timestamps = true;
     protected $fillable = [
        'team_id',
        'full_name_member',
        'phone_number_member',
        'gender_member',
    ];
      

}
