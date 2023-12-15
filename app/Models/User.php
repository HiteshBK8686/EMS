<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

// use App\Models\Permission;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
// use Spatie\Permission\Models\Permission;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, HasPermissions, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'name',
        'unique_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
      
    ];
    public function sendPasswordResetNotification($token)
    {
        $url = url('/') . "/password-update?token=" . $token . '&email=' . $this->email;

        $this->notify(new ResetPasswordNotification($url));
    }
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function getAllPermissionsAttribute() {
    //     $permissions = [];
    //       foreach (Permission::all() as $permission) {
    //         if (Auth::user()->can($permission->name)) {
    //           $permissions[] = $permission->name;
    //         }
    //       }
    //     return $permissions;
    //}

    // Relationship
    public function user_information(): HasOne
    {
        return $this->hasOne(UserInformation::class, 'user_id', 'id');
    }

    public function employment_detail(): HasOne
    {
        return $this->hasOne(EmploymentDetail::class, 'user_id', 'id');
    }

    public function leave_applications(): HasOne
    {
        return $this->hasOne(LeaveApplication::class, 'user_id', 'id');
    }

    public function leave_tracker(): HasOne
    {
        return $this->hasOne(LeaveTracker::class, 'user_id', 'id');
    }
}
