<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemType extends Model {

	protected $table = 'system_types';
	protected $fillable = ['name', 'c', 'k'];
	protected $hidden = [];
}
