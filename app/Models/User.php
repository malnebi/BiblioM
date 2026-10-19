<?php

namespace App\Models;

use App\Notifications\AccountApproved;
use App\Notifications\UserPasswordReset;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, MustVerifyEmailTrait, Notifiable;

    public const FORMER_STUDENT = 'Бивши ученик';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'role_type',
        'role_details',
        'approved',
        'email_verification_required',
        'user_photo',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public static array $schoolRoles = [
        'Ученик' => [
            'I-1',
            'I-2',
            'I-3',
            'I-4',
            'II-1',
            'II-2',
            'II-3',
            'II-4',
            'III-1',
            'III-2',
            'III-3',
            'III-4',
            'IV-1',
            'IV-2',
            'IV-3',
            'IV-4',
        ],
        'Радник школе' => [],
        'Родитељ' => [],
        'Друго' => [],
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_verification_required' => 'boolean',
            'books_notification_viewed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new AccountApproved);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new UserPasswordReset($token));
    }

    public function library()
    {
        return $this->belongsTo(Library::class);
    }

    public function ownLibrary()
    {
        return $this->hasOne(Library::class, 'owner_id');
    }

    public function memberOfLibraries()
    {
        return $this->belongsToMany(Library::class, 'library_user', 'user_id', 'library_id');
    }

    public function books()
    {
        return $this->hasMany('App\Models\Book');
    }

    public function loans()
    {
        return $this->hasMany('App\Models\Loan');
    }

    public function profileChangeRequests()
    {
        return $this->hasMany(ProfileChangeRequest::class);
    }
}
