<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

	protected $table = 'projects';
	protected $fillable = ['name', 'created_by', 'started_at', 'expected_completed_at'];
	protected $hidden = [];

	public function users() {
		return $this->belongsToMany('App\User', 'project_users')->withPivot('is_manager')->orderBy('is_manager', 'DESC');
	}

	public function managers() {
		return $this->belongsToMany('App\User', 'project_users')->wherePivot('is_manager', '=', '1');
	}

	public function tasks() {
		return $this->hasMany('App\Task')->orderBy('started_at', 'DESC');
	}

	public function comments() {
		return $this->hasManyThrough('App\TaskComment', 'App\Task')->orderBy('updated_at', 'DESC');
	}

	public function milestones() {
		return $this->hasMany('App\Milestone');
	}

	public function createdBy() {
		return $this->hasOne('App\User', 'id', 'created_by');
	}

	public function functionPoints() {
		return $this->hasOne('App\FunctionPoint', 'project_id', 'id');
	}

	public function cocomoi() {
		return $this->hasOne('App\SystemType', 'id', 'system_type_id');
	}

	public function cocomoii() {
		return $this->hasOne('App\CocomoII', 'project_id', 'id');
	}
}
