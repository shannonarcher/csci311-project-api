<?php

use Illuminate\Http\Request;

use App\User;

class Sprint2Test extends TestCase {

	/*public function getAdminUser() {
		return User::where('is_admin', '=', 1)->first();
	}

	public function getUser() {
		return User::where('is_admin', '=', 0)->firstOrFail();
	}

	public function getLoggedInUser() {
		return $this->loginUser($this->getUser());
	}

	public function getLoggedInAdmin() {
		return $this->loginUser($this->getAdminUser());
	}

	public function loginUser($user) {
		$user->session_token = str_random(32);
		$user->save();
		return $user;
	}

	/** TESTS */
	/*public function testLogin()
	{
		$this->seed();

		$response = $this->call('POST', '/users/login', [
			"email" => "john@company.com",
			"password" => "admin"]);

		$this->assertEquals(200, $response->getStatusCode());
	}

	public function testCreation() 
	{
		$this->seed();

		$user = $this->getLoggedInAdmin();

		$response = $this->call('POST', '/users', [
			"name" => "Somebody",
			"email" => "somebody@liquidly.com", 
			"password" => str_random(20),
			"session_token" => $user->session_token ]);

		$this->assertEquals(200, $response->getStatusCode());

		$user = User::where("name", "=", "Somebody")->first();
		$this->assertNotNull($user);
	}

	public function testGetAll() 
	{
		$this->seed();

		$user = $this->getLoggedInAdmin();

		$response = $this->call('GET', '/users', [
			"session_token" => $user->session_token ]);

		$this->assertEquals(200, $response->getStatusCode());
		$this->assertNotNull(json_decode($response->getContent()));
		$this->assertEquals(3, count(json_decode($response->getContent())));
	}

	public function testGet() 
	{
		$this->seed();

		$user = $this->getLoggedInAdmin();
		$user2 = $this->getUser();

		$response = $this->call('GET', '/users/'.$user2->id, [
			"session_token" => $user->session_token]);

		$this->assertEquals(200, $response->getStatusCode());
		$this->assertNotNull(json_decode($response->getContent()));		
	}

	public function testUpdate() 
	{
		$this->seed();

		$user = $this->getLoggedInUser();

		$response = $this->call('PUT', '/users/'.$user->id, [
			"name" => "My New Name",
			"session_token" => $user->session_token ]);

		file_put_contents("log.html", $response->getContent());

		$this->assertEquals(200, $response->getStatusCode());

		$user = json_decode($response->getContent());
		$this->assertEquals("My New Name", $user->name);
	}

	public function testArchiveUser() 
	{
		$this->seed();

		$user = $this->getLoggedInAdmin();
		$user2 = $this->getUser();

		$response = $this->call('POST', '/users/'.$user2->id.'/archive', [
			"session_token" => $user->session_token]);

		$this->assertEquals(200, $response->getStatusCode());

		$user = json_decode($response->getContent());
		$this->assertEquals(1, $user->is_archived);
	}

	public function testUnArchiveUser() 
	{
		$this->seed();

		$user = $this->getLoggedInAdmin();
		$user2 = $this->getUser();

		$user2->is_archived = 1;
		$user2->save();

		$response = $this->call('POST', '/users/'.$user2->id.'/unarchive', [
			"session_token" => $user->session_token]);

		$this->assertEquals(200, $response->getStatusCode());

		$user = json_decode($response->getContent());
		$this->assertEquals(0, $user->is_archived);
	}

	public function testResetPassword() 
	{
		$this->seed();

		$user = $this->getLoggedInAdmin();
		$user2 = $this->getUser();

		$response = $this->call('POST', '/users/'.$user2->id.'/password/reset', [
			"session_token" => $user->session_token]);

		$this->assertEquals(200, $response->getStatusCode());
	}*/
}
