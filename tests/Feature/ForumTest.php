<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForumTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;
    private User $other;
    private Thread $thread;
    private Topic $topic;
    private Reply $reply;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->admin->role = 'admin';
        $this->admin->save();
        $this->user = User::factory()->create();
        $this->other = User::factory()->create();

        $this->thread = Thread::create(['title' => 'PHP', 'description' => 'Vragen over PHP', 'user_id' => $this->admin->id]);
        $this->topic = Topic::create(['title' => 'Mijn vraag', 'body' => 'De hele vraag', 'thread_id' => $this->thread->id, 'user_id' => $this->user->id]);
        $this->reply = Reply::create(['body' => 'Mijn antwoord', 'topic_id' => $this->topic->id, 'user_id' => $this->user->id]);
    }

    private function topicUrl(string $suffix = ''): string
    {
        return "/thread/{$this->thread->id}/topic/{$this->topic->id}{$suffix}";
    }

    private function replyUrl(string $suffix = ''): string
    {
        return $this->topicUrl("/reply/{$this->reply->id}{$suffix}");
    }

    public function test_guests_can_read_the_forum_but_not_see_the_forms(): void
    {
        $this->get('/')->assertOk()->assertSee('PHP');
        $this->get("/thread/{$this->thread->id}")->assertOk()->assertSee('Mijn vraag')->assertDontSee('Nieuwe topic');
        $this->get($this->topicUrl())->assertOk()->assertSee('De hele vraag')->assertSee($this->user->username)
            ->assertSee('Mijn antwoord')->assertDontSee('Jouw reactie');
    }

    public function test_guests_cannot_post(): void
    {
        $this->post("/thread/{$this->thread->id}/topic", ['title' => 'x', 'body' => 'y'])->assertRedirect('/login');
        $this->post($this->topicUrl('/reply'), ['body' => 'y'])->assertRedirect('/login');
        $this->assertSame(1, Topic::count());
        $this->assertSame(1, Reply::count());
    }

    public function test_a_user_can_create_a_topic_and_a_reply(): void
    {
        $this->actingAs($this->user)->post("/thread/{$this->thread->id}/topic", ['title' => 'Nieuw', 'body' => 'Tekst'])->assertRedirect();
        $this->assertDatabaseHas('topics', ['title' => 'Nieuw', 'user_id' => $this->user->id, 'thread_id' => $this->thread->id]);

        $this->actingAs($this->user)->post($this->topicUrl('/reply'), ['body' => 'Nieuwe reactie'])->assertRedirect();
        $this->assertDatabaseHas('replies', ['body' => 'Nieuwe reactie', 'user_id' => $this->user->id]);
    }

    public function test_input_is_validated(): void
    {
        $this->actingAs($this->user)->post("/thread/{$this->thread->id}/topic", ['title' => '', 'body' => ''])
            ->assertSessionHasErrors(['title', 'body']);
        $this->actingAs($this->user)->post($this->topicUrl('/reply'), ['body' => ''])->assertSessionHasErrors('body');
    }

    public function test_a_user_can_edit_their_own_topic_and_reply(): void
    {
        $this->actingAs($this->user)->get($this->topicUrl('/edit'))->assertOk();
        $this->actingAs($this->user)->patch($this->topicUrl(), ['title' => 'Aangepast', 'body' => 'Aangepast'])->assertRedirect();
        $this->assertSame('Aangepast', $this->topic->fresh()->title);

        $this->actingAs($this->user)->get($this->replyUrl('/edit'))->assertOk();
        $this->actingAs($this->user)->patch($this->replyUrl(), ['body' => 'Aangepast'])->assertRedirect();
        $this->assertSame('Aangepast', $this->reply->fresh()->body);
    }

    public function test_a_user_cannot_edit_someone_elses_topic_or_reply(): void
    {
        $this->actingAs($this->other)->get($this->topicUrl('/edit'))->assertForbidden();
        $this->actingAs($this->other)->patch($this->topicUrl(), ['title' => 'Hack', 'body' => 'Hack'])->assertForbidden();
        $this->actingAs($this->other)->patch($this->replyUrl(), ['body' => 'Hack'])->assertForbidden();
        $this->assertSame('Mijn vraag', $this->topic->fresh()->title);
    }

    public function test_a_user_cannot_delete_or_create_threads_or_manage_users(): void
    {
        $this->actingAs($this->user)->delete($this->replyUrl())->assertForbidden();
        $this->actingAs($this->user)->delete($this->topicUrl())->assertForbidden();
        $this->actingAs($this->user)->delete("/thread/{$this->thread->id}")->assertForbidden();
        $this->actingAs($this->user)->get('/threads/create')->assertForbidden();
        $this->actingAs($this->user)->post('/threads', ['title' => 'x', 'description' => 'y'])->assertForbidden();
        $this->actingAs($this->user)->get('/users')->assertForbidden();
        $this->actingAs($this->user)->get("/users/{$this->other->id}")->assertForbidden();
        $this->actingAs($this->user)->delete("/users/{$this->other->id}")->assertForbidden();
        $this->assertSame(1, Thread::count());
        $this->assertSame(1, Reply::count());
    }

    public function test_a_user_cannot_delete_their_own_account(): void
    {
        $this->actingAs($this->user)->delete('/profile')->assertStatus(405);
        $this->assertNotNull($this->user->fresh());
    }

    public function test_registering_cannot_create_an_admin(): void
    {
        $this->post('/register', [
            'name' => 'Nieuw', 'username' => 'nieuw', 'email' => 'nieuw@example.com',
            'password' => 'password', 'password_confirmation' => 'password', 'role' => 'admin',
        ]);

        $this->assertSame('user', User::where('username', 'nieuw')->first()->role);
    }

    public function test_an_admin_can_manage_threads_and_delete_content(): void
    {
        $this->actingAs($this->admin)->get('/threads/create')->assertOk();
        $this->actingAs($this->admin)->post('/threads', ['title' => 'Laravel', 'description' => 'Vragen'])->assertRedirect();
        $this->assertDatabaseHas('threads', ['title' => 'Laravel', 'user_id' => $this->admin->id]);

        $this->actingAs($this->admin)->delete($this->replyUrl())->assertRedirect();
        $this->assertSame(0, Reply::count());
        $this->actingAs($this->admin)->delete($this->topicUrl())->assertRedirect();
        $this->assertSame(0, Topic::count());
    }

    public function test_the_users_pages_never_show_a_password(): void
    {
        $this->actingAs($this->admin)->get('/users')->assertOk()->assertSee($this->user->username)
            ->assertDontSee($this->user->email)->assertDontSee($this->user->password);
        $this->actingAs($this->admin)->get("/users/{$this->user->id}")->assertOk()->assertSee($this->user->email)
            ->assertDontSee($this->user->password);
    }

    public function test_an_admin_can_update_and_delete_a_user(): void
    {
        $this->actingAs($this->admin)->patch("/users/{$this->user->id}", [
            'name' => 'Nieuwe naam', 'username' => 'nieuwenaam', 'email' => 'nieuw@example.com', 'role' => 'admin',
        ])->assertRedirect();
        $this->assertSame('nieuwenaam', $this->user->fresh()->username);
        $this->assertSame('admin', $this->user->fresh()->role);

        $this->actingAs($this->admin)->delete("/users/{$this->user->id}")->assertRedirect('/users');
        $this->assertNull($this->user->fresh());
        $this->assertSame(0, Topic::count(), 'De berichten van de user worden ook verwijderd');
    }

    public function test_an_admin_cannot_delete_or_demote_themself(): void
    {
        $this->actingAs($this->admin)->delete("/users/{$this->admin->id}")->assertSessionHasErrors('delete');
        $this->assertNotNull($this->admin->fresh());

        $this->actingAs($this->admin)->patch("/users/{$this->admin->id}", [
            'name' => 'Admin', 'username' => $this->admin->username, 'email' => $this->admin->email, 'role' => 'user',
        ])->assertSessionHasErrors('role');
        $this->assertSame('admin', $this->admin->fresh()->role);
    }

    public function test_a_topic_url_must_match_its_thread(): void
    {
        $otherThread = Thread::create(['title' => 'React', 'description' => 'Vragen', 'user_id' => $this->admin->id]);

        $this->get("/thread/{$otherThread->id}/topic/{$this->topic->id}")->assertNotFound();
    }
}
