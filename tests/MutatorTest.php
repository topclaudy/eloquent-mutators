<?php

use Awobaz\Mutator\Database\Eloquent\Model;
use Awobaz\Mutator\Tests\Model\Post;

require_once __DIR__. '/TestCase.php';

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
        $post->save();

        $this->assertEquals($post->title, '*Mutators are nice');

        Model::unguard();
    }
}