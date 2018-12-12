<?php

namespace Awobaz\Mutator\Tests\Model;

use Awobaz\Mutator\Database\Eloquent\Model;

class Post extends Model
{
    protected $getters = [
        'title' => ['trim_space', 'nice'],
    ];

    protected $setters = [
        'title' => ['prepend_star']
    ];
}