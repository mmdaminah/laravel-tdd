<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_activity_has_a_user()
    {
       $user = $this->signIn();
       $project = ProjectFactory::ownedBy($user)->create();
       $this->assertEquals($user->id, $project->activities->first()->user->id);
    }
}
