<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	protected $table = 'users';
	protected $fillable = ['name', 'email', 'password'];
	protected $hidden = ['password', 'session_token', 'remember_token'];

	public function projects() {
		return $this->belongsToMany('App\Project', 'project_users')->withPivot('is_manager');
	}

	public function skills() {
		return $this->hasMany('App\Skill');
	}
}
