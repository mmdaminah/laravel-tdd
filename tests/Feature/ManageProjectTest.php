<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageProjectTest extends TestCase
{

    use RefreshDatabase;

    public function test_guests_cannot_create_post()
    {
        $attributes = Project::factory()->raw();
        $this->post('/projects', $attributes)->assertRedirect('/login');
    }

    public function test_guests_cannot_view_projects()
    {
        $this->get('/projects')->assertRedirect('/login');
    }

    public function test_guests_cannot_view_a_single_project()
    {
        $project = Project::factory()->create();
        $this->get($project->path())->assertRedirect('/login');
    }

    public function test_a_user_can_create_project(): void
    {
        $this->signIn();

        $attributes = Project::factory()->raw();

        $response = $this->followingRedirects()->post('/projects', $attributes);

        $response
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);

    }

    public function test_a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        $user = $this->signIn();
        $project = tap(ProjectFactory::create())->invite($user);
        $this->get('/projects')->assertSee($project->title);
    }

    public function test_unauthorized_cannot_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $user = $this->signIn();

        $this->delete($project->path())->assertStatus(403);

        $project->invite($user);

        $this->actingAs($user)->delete($project->path())->assertStatus(403);
    }

    public function test_a_user_can_delete_a_project()
    {
        $project = ProjectFactory::create();
        $this->actingAs($project->owner)->delete($project->path())
            ->assertRedirect('/projects');
        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    public function test_a_user_can_update_project()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->patch($project->path(), ['title' => 'changed', 'description' => 'changed', 'notes' => "changed"])->assertRedirect($project->path());
        $this->get($project->path())->assertSee('changed');
        $this->assertDatabaseHas('projects', ['notes' => 'changed', 'title' => 'changed', 'description' => 'changed']);
    }

    public function test_a_user_can_update_general_notes()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->patch($project->path(), ['notes' => "changed"])->assertRedirect($project->path());
        $this->get($project->path())->assertSee('changed');
        $this->assertDatabaseHas('projects', ['notes' => 'changed']);

    }

    public function test_a_project_requires_title()
    {

        $this->signIn();

        $attributes = Project::factory()->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function test_a_project_requires_description()
    {

        $this->signIn();

        $attributes = Project::factory()->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    public function test_users_can_view_their_projects()
    {
        $this->signIn();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_authenticated_user_cannot_view_others_projects()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $this->get($project->path())->assertStatus(403);
    }

    public function test_an_authenticated_user_canot_update_ohter_projects()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $this->patch($project->path(), ['notes' => 'changed'])->assertStatus(403);
    }

}
