<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'lastname',
        'firstname',
        'active',
        'avatar',
        'birthday',
        'gender_id',
        'indentity',
        'mobile',
        'address',
        'origin_place',
        'department_id',
        'status_id',
        'contract_started',
        'contract_finished',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function department()
    {
        return $this->hasOne(Department::class);
    }
    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        $user = Auth::user();

        // Lấy danh sách quyền của người dùng từ bảng Role
        $roles = $user->roles()->get();

        // Tạo mảng chứa toàn bộ quyền của người dùng
        $permissions = [];

        foreach ($roles as $role) {
            // Lấy danh sách quyền từ mỗi vai trò của người dùng
            $permissions = array_merge($permissions, $role->permissions()->pluck('name')->toArray());
        }

        // Xóa các quyền trùng lặp và sắp xếp lại mảng
        $permissions = array_unique($permissions);
        sort($permissions);

        return $permissions;
    }

    public function checkPermission(string $permission): bool
    {
        $permissions = $this->permissions();
        return in_array($permission, $permissions);
    }

    public function checkRole(string $role): bool
    {
        $user = Auth::user();
        $roles = $user->roles()->pluck('name')->toArray();
        // Lấy danh sách tên các vai trò của người dùng
        return in_array($role, $roles);
    }

    public function checkActive(): bool
    {
        $user = Auth::user();
        return $user->active;
    }
}
