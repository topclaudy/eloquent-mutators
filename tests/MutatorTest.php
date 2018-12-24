<?php

use Awobaz\Mutator\Database\Eloquent\Model;
use Awobaz\Mutator\Tests\Model\Post;

require_once __DIR__.'/TestCase.php';
require_once __DIR__.'/Models/Post.php';

class MutatorTest extends TestCase
{
    /**
     * Test the save method on a relationship
     *
     * @return void
     */
    public function testMutator()
    {
        Model::unguard();

        $post = new Post();
        $post->title = 'Mutators      are     awesome   ';
        $post->content = 'Real interesting';
        $post->save();

        $raw = $post->getAttributes();
        $this->assertEquals('*Mutators      are     awesome   ', $raw['title']);
        $this->assertEquals('*Mutators are nice', $post->title);

        $this->assertEquals('mutators-are-awesome', $post->slug);
        $this->assertEquals($post->slug, $raw['slug']);

        $this->assertEquals('Real interesting', $post->content);
        $this->assertEquals('Real interesting', $raw['content']);

        $post->content = 'one';
        $raw = $post->getAttributes();
        $this->assertEquals('two', $post->content);
        $this->assertEquals('one', $raw['content']);

        $post->content = 'three four';
        $raw = $post->getAttributes();
        $this->assertEquals('five five', $post->content);
        $this->assertEquals('five five', $raw['content']);

        $post->content = 'I saw Batman';
        $raw = $post->getAttributes();
        $this->assertEquals('I saw Bruce Wayne', $post->content);
        $this->assertEquals('I saw Bruce Wayne', $raw['content']);

        Model::unguard();
    }
}