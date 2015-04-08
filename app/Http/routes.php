<?php

// Sprint 1
/** USER ROUTES **/
Route::post('/users', ['middleware' => ['auth', 'isAdmin'], 'uses' => 'UserController@create']);
Route::get('/users', ['middleware' => 'auth', 'uses' => 'UserController@getAll']);
Route::get('/users/{user}', ['middleware' => 'auth', 'uses' => 'UserController@get']);
Route::put('/users/{user}', ['middleware' => ['auth', 'isUserOwner'], 'uses' => 'UserController@update']);

Route::post('/users/{user}/archive', ['middleware' => ['auth', 'isAdmin'], 'uses' => 'UserController@archive']);
Route::post('/users/{user}/unarchive', ['middleware' => ['auth', 'isAdmin'], 'uses' => 'UserController@unarchive']);

Route::post('/users/login', ['uses' => 'UserController@login']);

Route::post('/users/{user}/password/reset', ['middleware' => ['auth', 'isAdmin'], 'uses' => 'UserController@resetPassword']);

/** PROJECT ROUTES **/
Route::post('/projects', ['uses' => 'ProjectController@create']);
Route::get('/projects', ['uses' => 'ProjectController@getAll']);
Route::get('/projects/{project}', ['uses' => 'ProjectController@get']);
Route::get('/projects/archived', ['uses' => 'ProjectController@getArchived']);
Route::put('/projects/{project}', ['uses' => 'ProjectController@update']);

Route::post('/projects/{project}/archive', ['uses' => 'ProjectController@archive']);
Route::post('/projects/{project}/unarchive', ['uses' => 'ProjectController@unarchive']);

Route::post('/projects/{project}/assignManager/{user}', ['uses' => 'ProjectController@assignManager']);
Route::post('/projects/{project}/assign/{user}', ['uses' => 'ProjectController@assignUser']);

// Sprint 2
/*Route::post('/projects/{project}/tasks', ['uses' => 'ProjectController@createTask']);
Route::get('/projects/{project}/tasks', ['uses' => 'ProjectController@getTasks']);

Route::post('/projects/{project}/milestones', ['uses' => 'ProjectController@createMilestone']);
Route::get('/projects/{project}/milestones', ['uses' => 'ProjectController@getMilestones']);

Route::get('/projects/{project}/comments', ['uses' => 'ProjectController@getComments']);

/** TASK ROUTES **//*
Route::get('/tasks/{task}', ['uses' => 'TaskController@get']);
Route::put('/tasks/{task}', ['uses' => 'TaskController@update']);
Route::delete('/tasks/{task}', ['uses' => 'TaskController@delete']);

Route::post('/tasks/{task}/delete/approve', ['uses' => 'TaskController@approveDeletion']);
Route::post('/tasks/{task}/delete/reject', ['uses' => 'TaskController@rejectDeletion']);

Route::post('/tasks/{task}/approve', ['uses' => 'TaskController@approveTask']);

Route::post('/tasks/{task}/assign/{user}', ['uses' => 'TaskController@assignUser']);

Route::post('/tasks/{task}/comments', ['uses' => 'TaskController@createComment']);
Route::get('/tasks/{task}/comments', ['uses' => 'TaskController@getComments']);

Route::post('/tasks/{task}/relates-with/{milestone}', ['uses' => 'TaskController@createRelationWithMilestone']);
Route::delete('/tasks/{task}/relates-with/{milestone}', ['uses' => 'TaskController@deleteRelationWithMilestone']);

Route::post('/tasks/{task}/depends-on/{task}', ['uses' => 'TaskController@createDependency']);
Route::delete('/tasks/{task}/depends-on/{task}', ['uses' => 'TaskController@deleteDependency']);

/** TASK COMMENT ROUTES **//*
Route::get('/comments/{comment}', ['uses' => 'TaskCommentController@get']);
Route::put('/comments/{comment}', ['uses' => 'TaskCommentController@update']);

Route::post('/comments/{comment}/archive', ['uses' => 'TaskCommentController@archive']);
Route::post('/comments/{comment}/unarchive', ['uses' => 'TaskCommentController@archive']);

/** MILESTONE ROUTES **//*
Route::get('/milestones/{milestone}', ['uses' => 'MilestoneController@get']);
Route::put('/milestones/{milestone}', ['uses' => 'MilestoneController@update']);
Route::delete('/milestones/{milestone}', ['uses' => 'MilestoneController@delete']);

Route::get('/milestones/{milestone}/tasks', ['uses' => 'MilestoneController@getTasks']);

/****/