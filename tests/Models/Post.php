<?php

namespace Awobaz\Mutator\Tests\Model;

use Awobaz\Mutator\Database\Eloquent\Model;

class Post extends Model
{
    protected $accessors = [
        'title' => ['trim_whitespace', 'remove_extra_whitespace', 'nice'],
    ];

    protected $mutators = [
        'title' => ['prepend_star']
    ];
}