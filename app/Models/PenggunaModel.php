<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 

class PenggunaModel extends Authenticatable
{
    use HasFactory;

    // Specify the table name if it's different from the default
    protected $table = 'm_pengguna'; // Replace with your actual table name if different

    // Set the primary key if it's not the default 'id'
    protected $primaryKey = 'id_pengguna';

    // Disable automatic timestamps if you're using custom timestamp names (created_at, updated_at)
    public $timestamps = true;

    // Define the fillable attributes (columns that can be mass-assigned)
    protected $fillable = [
        'id_jenis_pengguna', 
        'nama_pengguna',
        'username', 
        'password', 
        'email', 
        'foto_profil'
    ];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];

    // Define the relationships (if any)
    public function jenisPengguna()
    {
        return $this->belongsTo(JenisPenggunaModel::class, 'id_jenis_pengguna', 'id_jenis_pengguna');
    }

    // Mutator to hash the password before saving
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // Accessor to get the user's profile photo URL
    public function getFotoProfilAttribute($value)
    {
        return asset('storage/' . $value);  // Assuming you are storing profile photos in the storage folder
    }

    // Method to retrieve the user's full name (if applicable)
    public function getFullNameAttribute()
    {
        return $this->nama_pengguna . ' (' . $this->username . ')';
    }
}
