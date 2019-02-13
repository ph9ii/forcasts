<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;


    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    /**
     * A guest cannot favorite anything.
     *
     * @return void
     */
    public function test_guest_cannot_favorite_anything()
    {       
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }
    
    /**
     * A user can favorite any reply.
     *
     * @return void
     */
    public function test_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');
        
        $this->post('replies/'. $reply->id .'/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /**
     * A user can unfavorite any reply.
     *
     * @return void
     */
    public function test_user_can_unfavorite_any_reply()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $reply = create('App\Reply');
        
        $reply->favorite();

        $this->delete('replies/'. $reply->id .'/favorites');

        $this->assertCount(0, $reply->favorites);
    }

    /**
     * A user can only favorite reply once.
     *
     * @return void
     */
    public function test_user_can_only_favorite_reply_once()
    {
        $this->signIn();
        
        $reply = create('App\Reply');
        
        try {
            $this->post('replies/'. $reply->id .'/favorites');
            $this->post('replies/'. $reply->id .'/favorites');
        } catch(\Exception $e) {
            $this->fail('Did not expect to insert same record twice');
        }

        // dd(\App\Favorite::all()->toArray());

        $this->assertCount(1, $reply->favorites);
    }
}