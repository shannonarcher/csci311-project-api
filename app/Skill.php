<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model {

	protected $table = 'skills';
	protected $fillable = ['name'];
	protected $hidden = [];

	public function users() {
		return $this->belongsToMany('App\User', 'users_skills', 'user_id', 'skill_id');
	}
}
