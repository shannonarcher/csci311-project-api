<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

	protected $table = 'tasks';
	protected $fillable = ['title', 'description', 'started_at', 'optimistic_duration', 'estimation_duration', 'pessimistic_duration', 'approved_by', 'approved_at', 'priority'];
	protected $hidden = [];

	public function comments() {
		return $this->hasMany('App\TaskComment');
	}

	public function project() {
		return $this->belongsTo('App\Project');
	}

	public function approvedBy() {
		return $this->belongsTo('App\User', 'approved_by');
	}

	public function createdBy() {
		return $this->belongsTo('App\User', 'created_by');
	}

	public function parent() {
		return $this->belongsTo('App\Task', 'parent_id');
	}

	public function children() {
		return $this->hasMany('App\Task', 'parent_id');
	}

	public function dependencies() {
		return $this->belongsToMany('App\Task', 'task_dependencies', 'dependent_id', 'independent_id');
	}

	public function resources() {
		return $this->belongsToMany('App\User', 'task_resources', 'task_id', 'user_id');
	}
}
