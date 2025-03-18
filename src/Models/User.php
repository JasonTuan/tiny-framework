<?php

namespace TinyFramework\Models;

use TinyFramework\Support\DatabaseTrait;

class User
{
    use DatabaseTrait;

    public const string TABLE_NAME = 'demo_users';
    public const string PRIMARY_KEY = 'id';

    public function __construct(
        public ?int $id = null,
        public ?string $username = null,
        public ?string $email = null,
    ) {
    }
}
