<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'group_type',
        'client_id',
        'status',
    ];

    /**
     * AdminLTE Integration: User Image
     */
    public function adminlte_image()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Default avatar for different group types
        $color = $this->group_type === 'ADMIN' ? 'ff0000' : ($this->group_type === 'CASTRO' ? '007bff' : '17a2b8');
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&color=ffffff&background=$color&size=128";
    }

    /**
     * AdminLTE Integration: User Description (Sub-title)
     */
    public function adminlte_desc()
    {
        return $this->group_type . " - Nivel " . $this->hierarchy_level;
    }

    /**
     * AdminLTE Integration: Profile URL
     */
    public function adminlte_profile_url()
    {
        return route('admin.users.edit', $this->id);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the users effective hierarchy level based on their roles.
     * Rule: Level 1 is highest priority.
     */
    public function getHierarchyLevelAttribute(): int
    {
        $min = $this->roles()->min('hierarchy_level');
        return $min ?? 5; // Default is the lowest operational level
    }
}
