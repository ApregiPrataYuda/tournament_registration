<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
class Teams extends Model
{
     use HasFactory;
     use SoftDeletes;
    protected $table = 'teams';
    protected $primaryKey = 'team_id';
    public $incrementing = true;
    public $timestamps = true;
       protected $fillable = [
        'team_name',
        'captain_id'
    ];

 //opsional
    public function scopeOnlyDeleted(Builder $query, bool $only = false): Builder
    {
        return $only ? $query->onlyTrashed() : $query;
    }



public function scopeSearch($query, $search)
{
    if ($search) {
        return $query->where(function ($q) use ($search) {
            $q->where('teams.team_name', 'like', "%{$search}%")
               ->orWhere('users.fullname', 'like', "%{$search}%")
               ->orWhere('team_members.full_name_member', 'like', "%{$search}%");
        });
    }
    return $query;
}



// Scope untuk sorting dinamis
public function scopeSort($query, $sortBy, $sortDir)
{
    return $query->orderBy($sortBy ?? 'created_at', $sortDir ?? 'asc');
}

public static function isDuplicate(array $data, $id = null): array
{
    $errors = [];

    $query = static::where('team_name', $data['team_name']);

    if ($id) {
        $query->where('id', '!=', $id); // Kecualikan ID yang sedang diupdate
    }

    if ($query->exists()) {
        $errors['team_name'] = ['team name sudah ada.'];
    }

    return $errors;
}
}
