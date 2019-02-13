<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;


    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }
    
    /**
     * A user can see all threads test.
     *
     * @return void
     */
    public function test_user_can_see_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);

        // $response->assertStatus(200);
    }

     /**
     * A user can see a single thread test.
     *
     * @return void
     */
    public function test_user_can_view_single_thread()
    {
        $this->get('/threads/' . $this->thread->channel->slug . '/' . $this->thread->id)
            ->assertSee($this->thread->title);
    }

    /**
     * A user can see replies that belongs to the thread.
     *
     * @return void
     */
    public function test_user_can_see_replies()
    {
        // given we have a reply
        $reply = create('App\Reply', ['thread_id' => $this->thread->id]);

        $this->get('threads/' . $this->thread->channel->slug . '/' . $this->thread->id)
            ->assertSee($reply->body);
    }

      /**
     * Filter threads by channel.
     *
     * @return void
     */
    public function test_filter_thread_by_channel()
    {
        $channel = create('App\Channel');

        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);

        $threadNotInChannel = create('App\Thread');

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

     /**
     * User filter threads by any username.
     *
     * @return void
     */
    public function test_user_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $threadByJohnDoe = create('App\Thread', ['user_id' => auth()->id()]);

        $threadNotByJohnDoe = create('App\Thread');

        $this->get('/threads?by=JohnDoe')
            ->assertSee($threadByJohnDoe->title)
            ->assertDontSee($threadNotByJohnDoe->title);
    }

     /**
     * User filter threads by popular.
     *
     * @return void
     */
    public function test_user_filter_threads_by_popular()
    {
        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    } 
}
