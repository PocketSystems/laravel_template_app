<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $appends = ["company","isExpired","subscriptionLeft"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getCompanyAttribute(){
        return Company::where("id",$this->attributes['company_id'])->get()->first();
    }

    public function getIsExpiredAttribute(){
        return (Company::where("id",$this->attributes['company_id'])
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())->count() <= 0);
    }

    public function getSubscriptionLeftAttribute(){
        $companyData = (Company::where("id",$this->attributes['company_id'])->get()->first());
        $endDate = Carbon::parse($companyData->end_date);
        return $endDate->isAfter(Carbon::now()) ? $endDate->diffInDays(Carbon::now()) : 0;
    }


}
