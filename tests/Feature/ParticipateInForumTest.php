<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
	use DatabaseMigrations;


    /**
     * Unauthenticated user cannot reply to thread.
     *
     * @return void
     */
    public function test_guest_cannot_reply_to_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/threads/1/replies', []);
    }

    /**
     * Authenticated user can reply to thread.
     *
     * @return void
     */
    public function test_user_can_reply_to_thread()
    {
    	// $this->be($user = create('App\User'));

        $this->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply');

        $this->post("/threads/{$thread->id}/replies", $reply->toArray());

        $this->get("/threads/{$thread->channel->slug}/{$thread->id}")
        	->assertSee($reply->body);
    }

    /**
     * Replies have errors
     *
     * @return void
     */
    public function test_replies_has_errors()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => null]);

        $this->post("/threads/{$thread->id}/replies", $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /**
     * Unauthorized users cannot delete replies
     *
     * @return void
     */
    public function test_unauth_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /**
     * Authorized users can delete replies
     *
     * @return void
     */
    public function test_auth_users_can_delete_replies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")
            ->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /**
     * Unauthorized users cannot update replies
     *
     * @return void
     */
    public function test_unauth_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /**
     * Authorized users can update replies
     *
     * @return void
     */
    public function test_auth_users_can_update_replies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $updatedBody = 'Some updated body';

        $this->patch("/replies/{$reply->id}", ['body' => $updatedBody]);

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id, 
            'body' => $updatedBody
        ]);
    }
}
