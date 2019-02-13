<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Activity;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ManageThreadTest extends TestCase
{
	use DatabaseMigrations;

    /**
     * guests cannot create thread.
     *
     * @return void
     */
    public function test_guest_cannot_create_thread()
    {
    	$this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());
    }

    /**
     * A user can create thread.
     *
     * @return void
     */
    public function test_i()
    {
        $this->signIn();

        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        // dd($response->headers->get('location'));

        $this->get($response->headers->get('location'))
        	->assertSee($thread->title);
    }

    /**
     * guests cannot see create thread page.
     *
     * @return void
     */
    public function test_guest_cannot_see_create_thread_page()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->get('/threads/create')
            ->assertSee('/login');
    }

    /**
     * A thread have a title validation.
     *
     * @return void
     */
    public function test_thread_have_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /**
     * A thread have a channel that exists validation.
     *
     * @return void
     */
    public function test_thread_have_valid_channel()
    {
        $this->publishThread(['channel_id' => 153])
            ->assertSessionHasErrors('channel_id');
    }    


    /**
     * Publish thread.
     * $overrides array
     * @return $response
     */
    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        // dd($thread);

        return $this->post('/threads', $thread->toArray());
    }

    /**
     * Unauthorized user cannot delete any threads.
     *
     * @return void
     */
    public function test_unauth_user_cannot_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $response = $this->delete("/threads/{$thread->channel->id}/{$thread->id}");

        $response->assertRedirect('/login');

        $this->signIn();

        $this->delete("/threads/{$thread->channel->id}/{$thread->id}")
            ->assertStatus(403);
    }

    /**
     * A user can delete his threads only.
     *
     * @return void
     */
    public function test_auth_user_can_delete_his_own_threads_only()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->user()]);

        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        // dd("/threads/{$thread->channel->slug}/{$thread->id}");

        $response = $this->json('DELETE', "/threads/{$thread->channel->id}/{$thread->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        // $this->assertDatabaseMissing('activities', [
        //     'subject_id' => $thread->id,
        //     'subject_type' => get_class($thread)
        // ]);

        // $this->assertDatabaseMissing('activities', [
        //     'subject_id' => $reply->id,
        //     'subject_type' => get_class($reply)
        // ]);

        $this->assertEquals(0, Activity::count());
    } 

}
