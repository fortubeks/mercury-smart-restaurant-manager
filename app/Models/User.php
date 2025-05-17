<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'first_name',
        'last_name',
        'role_id',
        'restaurant_id',
        'user_id',
        'password',
        'is_active',
        'email_verified_at',
        'last_login',
        'current_shift',
        'google_id'
    ];

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

    protected static function boot()
    {
        parent::boot();

        // Define the created event listener
        static::created(function ($model) {
            //if the created user is a super admin, create a restaurant and assign it to the user
            if ($model->is_super_admin) {
                $restaurant = Restaurant::create(['user_id' => $model->id, 'name' => $model->name]);

                $model->restaurant_id = $restaurant->id;
                $model->user_id = $model->id;
                $model->save();
            }

            // Sync the roles based on the role name
            $model->roles()->sync($model->getRoleIds());
        });
    }

    public function getIsSuperAdminAttribute()
    {
        return optional($this->role)->name === Role::SUPER_ADMIN;
    }

    public function getIsProfileCompleteAttribute()
    {
        return $this->name && $this->email && $this->phone;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function getRoleIds()
    {
        // Define role mappings
        $roles = \App\Models\Role::pluck('id', 'name')->toArray();

        // then
        $roleMappings = [
            'Store Keeper' => [$roles['Store Keeper']],
            'Cashier' => [$roles['Cashier']],
            'Waiter' => [$roles['Waiter']],
            'Accountant' => [$roles['Accountant']],
            'Manager' => [
                $roles['Manager'],
                $roles['Cashier'],
                $roles['Waiter'],
                $roles['Accountant'],
                $roles['Host'],
                $roles['Store Keeper']
            ],
            'Super Admin' => array_values($roles), // all role IDs
        ];

        // Return role IDs based on the user's role
        return $roleMappings[$this->role->name] ?? [];
    }
}
