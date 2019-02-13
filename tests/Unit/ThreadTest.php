<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadTest extends TestCase
{
	use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }
    /**
     * A thread has replies.
     *
     * @return void
     */
    public function test_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /**
     * A thread has creator.
     *
     * @return void
     */
    public function test_thread_has_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /**
     * A thread has creator.
     *
     * @return void
     */
    public function test_thread_add_reply()
    {
        $this->thread->addReply([
            'user_id' => 1,
            'body' => 'Foobar'
        ]);
    }

    /**
     * A thread can make string path.
     *
     * @return void
     */
    public function test_thread_can_make_string_path()
    {
        $thread = create('App\Thread');

        $this->assertEquals("/forcasts/public/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
    }

    /**
     * A thread belongs to channel.
     *
     * @return void
     */
    public function test_thread_belongs_to_channel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }
}
