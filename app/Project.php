<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

	protected $table = 'projects';
	protected $fillable = ['name'];
	protected $hidden = [];

	public function users() {
		return $this->belongsToMany('App\User', 'project_users')->withPivot('is_manager');
	}

	public function tasks() {
		return $this->hasMany('App\Task');
	}

	public function comments() {
		return $this->hasManyThrough('App\TaskComment', 'App\Task');
	}

	public function milestones() {
		return $this->hasMany('App\Milestone');
	}

	public function createdBy() {
		return $this->hasOne('App\User', 'id', 'created_by');
	}
}