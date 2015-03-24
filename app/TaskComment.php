<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model {

	protected $table = 'task_comments';
	protected $fillable = ['comment'];
	protected $hidden = [];

	public function user() {
		return $this->belongsTo('App\User', 'created_by');
	}

	public function task() {
		return $this->belongsTo('App\Task');
	}
}
