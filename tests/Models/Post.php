<?php

namespace Awobaz\Mutator\Tests\Model;

use Awobaz\Mutator\Database\Eloquent\Model;

class Post extends Model
{
    protected $accessors = [
        'title'   => ['trim_whitespace', 'remove_extra_whitespace', 'nice'],
        'content' => [
            'replace_words' => ['two', 'one'],
        ],
    ];

    protected $mutators = [
        'title'   => ['prepend_star', 'copy_to' => 'slug'],
        'slug'    => 'slug',
        'content' => ['replace_words:five,three,four', 'preg_replace' => ['/Batman/', 'Bruce Wayne']],
    ];
}