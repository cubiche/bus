<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units\Middlewares\Handler\Resolver\NameOfMessage;

use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\FromClassNameResolver;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LoginUserMessage;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * FromClassNameResolver class.
 *
 * Generated by TestGenerator on 2016-04-07 at 15:40:41.
 */
class FromClassNameResolverTests extends TestCase
{
    /**
     * Test Resolve method.
     */
    public function testResolve()
    {
        $this
            ->given($resolver = new FromClassNameResolver())
            ->when($result = $resolver->resolve(new LoginUserMessage('ivan@cubiche.com', 'plainpassword')))
            ->then()
                ->string($result)
                    ->isEqualTo(LoginUserMessage::class)
        ;
    }
}