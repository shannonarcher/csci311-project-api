<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model {

	protected $table = 'milestones';
	protected $fillable = ['title', 'completed_at'];
	protected $hidden = [];

	public function user() {
		return $this->belongsTo('User', 'created_by');
	}

	public function tasks() {
		return $this->belongsToMany('Task', 'milestone_tasks');
	}

	public function project() {
		return $this->belongsTo('Project');
	}
}
