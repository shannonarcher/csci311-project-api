<!DOCTYPE html>
<html>
<head>
<title>Test Data</title>
</head>
<body>

<h1>Test Data</h1>
<hr />

<h1>Users</h1>
<div class="users">
<table>
<tr>
<th>Name</th>
<th>Email</th>
<th>Is Admin</th>
<th>Skills</th>
</tr>
@foreach ($users as $user) 
<tr>
<td>{{$user->name}}</td>
<td>{{$user->email}}</td>
<td>{{$user->is_admin ? "Yes" : "No"}}</td>
<td>
@foreach ($user->skills as $skill) 
{{ $skill->name }}, 
@endforeach
</td>
</tr>
@endforeach
</table>
</div>

<h1>Projects</h1>
<div class="projects">
@foreach ($projects as $project) 
<h2>Project: {{$project->name}}</h2>
<div class="project_details">
<table>
<tr>
<th>Start Date</th>
<td>{{$project->started_at}}</td>
</tr>
<tr>
<th>Expected Completion Date</th>
<td>{{$project->expected_completed_at}}</td>
</tr>
<tr>
<th>Created By</th>
<td>{{$project->createdBy->name}}</td>
</tr>
</table>
</div>

<div class="project_users">
	<h3>Project Managers</h3>
	<table>
		<tr>
			<th>Name</th>
			<th>Email</th>
		</tr>
		@foreach ($project->managers as $user) 
		<tr>
			<td>{{$user->name}}</td>
			<td>{{$user->email}}</td>
		</tr>
		@endforeach
	</table>
</div>

<div class="project_users">
	<h3>Team Members</h3>
	<table>
		<tr>
			<th>Name</th>
			<th>Email</th>
		</tr>
		@foreach ($project->users as $user) 
		<tr>
			<td>{{$user->name}}</td>
			<td>{{$user->email}}</td>
		</tr>
		@endforeach
	</table>
</div>
	
<div class="project_milestones">
	<h3>Project Milestones</h3>
	@foreach ($project->milestones as $milestone) 
	<div class="milestone">
	<h4>{{$milestone->title}} <small>{{$milestone->completed_at}}</small></h4>
	</div>
	@endforeach
</div>

<div class="project_tasks">
	<h3>Project Tasks</h3>
	@foreach ($project->tasks as $task) 
		<h4>Task {{$task->id}}: {{$task->title}}</h4>
		<table>
			<tr>
				<th>Task Id</th>
				<td>{{$task->id}}</td>
			</tr>
			<tr>
				<th>Description</th>
				<td>{{$task->description}}</td>
			</tr>
			<tr>
				<th>Start Date</th>
				<td>{{$task->started_at}}</td>
			</tr>
			<tr>
				<th>Estimation Duration</th>
				<td>{{($task->estimation_duration / 86400)}} days</td>
			</tr>
			<tr>
				<th>Progress</th>
				<td>{{$task->progress}}%</td>
			</tr>
			<!--<tr>
				<th>Completion Date</th>
				<td>{{$task->completed_at}}</td>
			</tr>-->
			<tr>
				<th>Approved By</th>
				<td>{{$task->approvedBy->name}}</td>
			</tr>
			<tr>
				<th>Created By</th>
				<td>{{$task->createdBy->name}}</td>
			</tr>
			@if (count($task->dependencies) > 0)
			<tr>
				<th>Dependencies</th>
				<td>
				<ul>
					@foreach ($task->dependencies as $d_task)
					<li>Task {{$d_task->id}}: {{$d_task->title}}</li>
					@endforeach
				</ul>
				</td>
			</tr>
			@endif
			@if ($task->parent)
			<tr>
				<th>Parent</th>
				<td>Task {{$task->parent->id}}: {{$task->parent->title}}</td>
			</tr>
			@endif
			<tr>
				<th>Resources</th>
				<td>
					<ul>
					@foreach($task->resources as $resource)
					<li>{{$resource->name}}</li>
					@endforeach
					</ul>
				</td>
			</tr>
		</table>

		<div class="task_comments">
		@foreach ($task->comments as $comment) 
			<h5>{{ $comment->createdBy->name }} @ {{$comment->created_at}} said</h5>
			<p>{{$comment->comment}}</p>
		@endforeach
		</div>
	@endforeach
</div>
@endforeach
</div>

</div>
</body>
</html>