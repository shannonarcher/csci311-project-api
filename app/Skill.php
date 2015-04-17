<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model {

	protected $table = 'user_skills';
	protected $fillable = ['name'];
	protected $hidden = [];

	public function user() {
		return $this->belongsTo('App\User');
	}
}
