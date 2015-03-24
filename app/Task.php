<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

	protected $table = 'tasks';
	protected $fillable = ['title', 'description', 'started_at', 'estimation_duration', 'approved_by', 'approved_at'];
	protected $hidden = [];

	public function user() {
		return $this->belongsTo('App\User', 'created_by');
	}

	public function comments() {
		return $this->hasMany('App\TaskComment');
	}

	public function milestones() {
		return $this->belongsToMany('App\Milestone', 'milestone_tasks');
	}

	public function project() {
		return $this->belongsTo('App\Project');
	}

	public function approvedBy() {
		return $this->belongsTo('App\User', 'approved_by');
	}
}
