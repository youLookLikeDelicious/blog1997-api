<?php

namespace App\Model;

use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\PasswordResetNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    
    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The guarded attribute
     *
     * @var string
     */
    protected $guarded = [];

    /**
     * The timestamp style
     *
     * @var string
     */
    protected $dateFormat = 'U';

    const genderTextMap = [
        'boy' => '男',
        'girl' => '女',
        'keep_secret' => '保密'
    ];

    public function getAvatarAttribute($val)
    {
        return $val ? URL::asset($val) : '';
    }
    /**
     * define has many ralation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany | null
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'manager_roles', 'user_id', 'role_id')
            ->withTimestamps();
    }

    /**
     * Convert gender to text
     *
     * @return string
     */
    public function getGenderTextAttribute()
    {
        return static::genderTextMap[$this->gender];
    }

    public function getStatusAttribute()
    {
        return $this->deleted_at
            ? ['code' => 2, 'text' => '已注销']
            : ($this->freeze_at ? ['code' => 3, 'text' => '已冻结'] : ['code' => 1, 'text' => '正常']);
    }

    /**
     * Define relation with social account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * check current user is master
     *
     * @return boolean
     */
    public function isMaster()
    {
        $this->load('roles:role.id,role.name');

        $masterRole = Role::select('id', 'name')->where('name', 'Master')->first();
        
        return $this->roles->contains($masterRole);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where([
            'deleted_at' => 0,
            'freeze_at' => 0
        ]);
    }

    /**
     * Define relation with log
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        if (!allowSendEmail()) {
            $this->markEmailAsVerified();
            return;
        }

        $this->notify(new VerifyEmailNotification);
    }
}
