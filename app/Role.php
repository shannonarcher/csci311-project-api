<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

	protected $table = 'roles';
	protected $fillable = ['name'];
	protected $hidden = [];

	public function users() {
		return $this->belongsToMany('App\User', 'users_roles');
	}
}
