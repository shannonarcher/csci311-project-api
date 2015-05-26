<?php

namespace app\spec\App\Http\Controllers;

use PhpSpec\Laravel\LaravelObjectBehavior;
use Prophecy\Argument;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Project;
use App\User;
use App\Http\Controllers\UserController;

class ProjectControllerSpec extends LaravelObjectBehavior
{
    private $session_token;

    function Let()
    {
        $userController = new UserController(new Request(['email' => 'john@company.com',
                                                         'password' => 'admin']));
        $response = $userController->login();

        $request = new Request(['session_token' => $response->getData()->session_token,
                                'name' => 'TEST',
                                'managers' => [1],
                                'started_at' => '2015-05-01',
                                'expected_completed_at' => '2015-05-11',
                                'title' => 'TEST',
                                'completed_at' => '2015-05-11',
                                'low_ilf' => 1, 'med_ilf' => 1, 'hi_ilf' => 1,
                                'low_eif' => 1, 'med_eif' => 1, 'hi_eif' => 1,
                                'low_ei' => 1, 'med_ei' => 1, 'hi_ei' => 1,
                                'low_eo' => 1, 'med_eo' => 1, 'hi_eo' => 1,
                                'low_eq' => 1, 'med_eq' => 1, 'hi_eq' => 1,
                                'gsc_1' => 1, 'gsc_2' => 1, 'gsc_3' => 1,
                                'gsc_4' => 1, 'gsc_5' => 1, 'gsc_6' => 1,
                                'gsc_7' => 1, 'gsc_8' => 1, 'gsc_9' => 1,
                                'gsc_10' => 1, 'gsc_11' => 1, 'gsc_12' => 1,
                                'gsc_13' => 1, 'gsc_14' => 1,
                                'kloc' => 1, 'system_type' => 1,
                                'PREC' => 1, 'FLEX' => 1, 'RESL' => 1,
                                'TEAM' => 1, 'PMAT' => 1,
                                'RCPX' => 1, 'RUSE' => 1, 'PDIF' => 1,
                                'PERS' => 1, 'PREX' => 1, 'FCIL' => 1,
                                'SCED' => 1
                                ]);
        $this->beConstructedWith($request);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Http\Controllers\ProjectController');
    }

    function it_can_create_a_new_project()
    {
        $this->create()->name->shouldBe('TEST');
    }

    function it_can_update_a_project()
    {
        $project = Project::first();
        $updatedProject = $this->update($project);
        $updatedProject->name->shouldBe('TEST');
        $updatedProject->actual_completed_at->shouldBe(null);
    }

    function it_can_get_all_projects()
    {

        $projects = $this->getAll();
        $projects->shouldReturnAnInstanceOf('Illuminate\Database\Eloquent\Collection');
        $projects->first()->users->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');

    }

    function it_can_get_an_individual_project()
    {
        $project = Project::first();
        $this->get($project)->shouldReturnAnInstanceOf('App\Project');
        $this->get($project)->users->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
        $this->get($project)->users->first()->skills->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
        $this->get($project)->users->first()->roles->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
        $this->get($project)->managers->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
        $this->get($project)->tasks->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
        $this->get($project)->createdBy->shouldBeAnInstanceOf('App\User');
        $this->get($project)->milestones->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
        $this->get($project)->functionPoints->shouldBeAnInstanceOf('App\FunctionPoint');
        $this->get($project)->cocomoi->shouldBeAnInstanceOf('App\SystemType');
        $this->get($project)->cocomoii->shouldBeAnInstanceOf('App\CocomoII');
    }

    function it_can_get_users_of_an_individual_project()
    {
        $project = Project::first();
        $this->getUsers($project)->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
    }

    function it_can_attach_detach_a_user_to_a_project()
    {
        $project = Project::first();
        $user = User::first();
        $this->attachUser($project, $user)->getData()->message->shouldStartWith($user->name);
        $this->detachUser($project, $user)->getData()->message->shouldStartWith($user->name);
    }

    function it_can_promote_demote_a_user_in_a_project()
    {
        $project = Project::first();
        $user = User::first();
        $this->promoteUser($project, $user)->getData()->message->shouldStartWith($user->name);
        $this->demoteUser($project, $user)->getData()->message->shouldStartWith($user->name);
    }

    function it_can_get_all_tasks_for_a_project()
    {
        $project = Project::first();
        $tasks = $this->getTasks($project);
        $tasks->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
        $tasks->first()->dependencies->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');

    }

    function it_can_add_remove_role_from_user_in_a_project()
    {
        $project = Project::first();
        $user = User::first();
        $this->addRoleToUser($project, $user)->getData()->message->shouldEndWith($user->name . '.');
        $this->removeRoleFromUser($project, $user)->getData()->message->shouldEndWith($user->name . '.');
    }

    function it_can_create_a_milestone_in_a_project()
    {
        $project = Project::first();
        $this->createMilestone($project)->title->shouldBe('TEST');
    }

    function it_can_get_all_milestones_for_a_project()
    {
        $project = Project::first();
        $this->getMilestones($project)->shouldReturnAnInstanceOf('Illuminate\Database\Eloquent\Collection');
    }

    function it_can_save_function_points_for_a_project()
    {
        $project = Project::first();
        $updatedProject = $this->saveFunctionPoints($project);
        $updatedProject->functionPoints->low_ilf->shouldBe(1);
    }

    function it_can_save_cocomo_for_a_project()
    {
        $project = Project::first();
        $this->saveCocomo($project)->shouldReturn(null);
    }

    function it_can_get_all_notifications_for_a_project()
    {
        $project = Project::first();
        $this->getNotifications($project)->shouldBeArray();
    }

    function it_can_archive_unarchive_a_project()
    {
        $project = Project::first();
        $this->archive($project)->getData()->message->shouldBe('Project archived.');
        $this->unarchive($project)->getData()->message->shouldBe('Project unarchived.');
    }
}
