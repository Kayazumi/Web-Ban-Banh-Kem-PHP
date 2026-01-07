<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'UserID';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'full_name',
        'phone',
        'address',
        'role',
        'status',
        'avatar',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Get the password attribute for authentication.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }


    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'last_login' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is staff
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user is customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id', 'UserID');
    }

    public function staffOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'staff_id', 'UserID');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id', 'UserID');
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'customer_id', 'UserID');
    }

    public function assignedComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'assigned_to', 'UserID');
    }

    public function complaintResponses(): HasMany
    {
        return $this->hasMany(ComplaintResponse::class, 'user_id', 'UserID');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'customer_id', 'UserID');
    }

    public function respondedContacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'responded_by', 'UserID');
    }

    public function cart(): HasMany
    {
        return $this->hasMany(Cart::class, 'user_id', 'UserID');
    }

    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'user_id', 'UserID');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class, 'created_by', 'UserID');
    }

    public function orderStatusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class, 'changed_by', 'UserID');
    }
}
