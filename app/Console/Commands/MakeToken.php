<?php

namespace App\Console\Commands;

use App\Token;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeToken extends Command
{
    /**
     * 命令行的名称及签名。
     *
     * @var string
     */
    protected $signature = 'token:make';

    /**
     * 命令行的描述
     *
     * @var string
     */
    protected $description = 'Create a Token';

    /**
     * 创建新的命令行实例。
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 执行命令行。
     *
     * @return mixed
     */
    public function handle()
    {
        $token = Str::random(32);
        Token::create(['token'=>$token]);
        echo "Token: {$token}" . PHP_EOL;
    }
}
