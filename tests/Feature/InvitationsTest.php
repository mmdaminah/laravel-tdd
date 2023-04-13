<?php

namespace Tests\Feature;

use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_owners_may_not_invite_users()
    {
        $project = ProjectFactory::create();
        $user = User::factory()->create();

        $assertInvitationForbidden = function () use($project, $user) {
            $this->actingAs($user)->post($project->path().'/invitations')
                ->assertStatus(403 );
        };

        $assertInvitationForbidden();

        $project->invite($user);

        $assertInvitationForbidden();
    }

    public function test_a_project_owner_can_invite_a_user()
    {
        $project = ProjectFactory::create();
        $userToInvite = User::factory()->create();
        $this->actingAs($project->owner)->post($project->path().'/invitations', [
            'email' => $userToInvite->email
        ])->assertRedirect($project->path());
        $this->assertTrue($project->members->contains($userToInvite));
    }

    public function test_invited_email_address_must_be_associated_with_a_valid_account()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->post($project->path().'/invitations', [
            'email' => 'notaUser@gmail.com'
        ])->assertSessionHasErrors([
            'email' => 'The user you are inviting must have an account.'
        ],null,'invitations');
    }

    public function test_invited_users_may_update_project_details()
    {
        $project = ProjectFactory::create();
        $project->invite($newUser = User::factory()->create());
        $this->signIn($newUser);
        $this->post($project->path().'/tasks', $tasks =  ['body'=> 'Foo task']);
        $this->assertDatabaseHas('tasks', $tasks );
    }
}
