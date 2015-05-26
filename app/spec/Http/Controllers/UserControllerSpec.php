<?php

namespace app\spec\App\Http\Controllers;

use PhpSpec\Laravel\LaravelObjectBehavior;
use Prophecy\Argument;

use Illuminate\Http\Request;
use App\User;
use App\Skill;
use App\Http\Controllers\UserController;

class UserControllerSpec extends LaravelObjectBehavior
{
    private $session_token;

    function Let()
    {

        $request = new Request([
            'email' => 'john@company.com',
            'password' => 'admin',
            'name' => 'TEST',
            'email' => 'test@example.com',
            'lang' => 'en']);

        $this->beConstructedWith($request);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Http\Controllers\UserController');
    }

    function it_can_login_a_user()
    {
        $this->login()->shouldReturnAnInstanceOf('Illuminate\Http\JsonResponse');
    }

    function it_can_create_a_new_user()
    {
        $this->create()->shouldReturnAnInstanceOf('Illuminate\Http\JsonResponse');
    }

    function it_can_get_all_users()
    {

        $users = $this->getAll();
        $users->shouldReturnAnInstanceOf('Illuminate\Database\Eloquent\Collection');
        $users->first()->projects->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
        $users->first()->skills->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
        $users->first()->roles->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
    }

    function it_can_get_an_individual_user()
    {
        $user = User::first();
        $this->get($user)->shouldReturnAnInstanceOf('App\User');
        $this->get($user)->projects->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
        $this->get($user)->skills->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
        $this->get($user)->roles->shouldBeAnInstanceOf('Illuminate\Database\Eloquent\Collection');
    }

    function it_can_update_a_user()
    {
        $user = User::where('name', '=', 'TEST')->get();
        $updatedUser = $this->update($user->first());
        $updatedUser->name->shouldBe('TEST');
        $updatedUser->email->shouldBe('test@example.com');
        $updatedUser->lang->shouldBe('en');
    }

    function it_can_archive_unarchive_a_user()
    {
        $user = User::first();
        $archivedUser = $this->archive($user);
        $archivedUser->is_archived->shouldBe(true);
        $unarchivedUser = $this->unarchive($user);
        $unarchivedUser->is_archived->shouldBe(false);
    }

    function it_can_reset_a_password()
    {
        $user = User::where('name', '=', 'TEST')->get()->first();
        $response = $this->resetPassword($user);
        $response->getData()->user->email->shouldEqual($user->email);
    }

}
