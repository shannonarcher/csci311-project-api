<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class FunctionPoint extends Model {

	protected $table = 'function_points';
	protected $fillable = ['low_ilf', 'med_ilf', 'hi_ilf',
							'low_eif', 'med_eif', 'hi_eif',
							'low_ei', 'med_ei', 'hi_ei',
							'low_eo', 'med_eo', 'hi_eo',
							'low_eq', 'med_eq', 'hi_eq',
							'gsc_1', 'gsc_2', 'gsc_3', 'gsc_4', 'gsc_5', 'gsc_6',
							'gsc_7', 'gsc_8', 'gsc_9', 'gsc_10', 'gsc_11', 'gsc_12',
							'gsc_13', 'gsc_14' ];
	protected $hidden = [];
}
