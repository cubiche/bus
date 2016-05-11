<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Tests\Units\Middlewares\Handler;

use Cubiche\Core\Bus\Middlewares\Handler\QueryHandlerMiddleware;
use Cubiche\Core\Bus\Middlewares\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass\HandlerClassResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName\MethodWithShortObjectNameResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfQuery\FromClassNameResolver;
use Cubiche\Core\Bus\Tests\Fixtures\Query\NearbyVenuesQuery;
use Cubiche\Core\Bus\Tests\Fixtures\Query\VenuesQueryHandler;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * QueryHandlerMiddlewareTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class QueryHandlerMiddlewareTests extends TestCase
{
    /**
     * Test handle method.
     */
    public function testHandle()
    {
        $this
            ->given(
                $resolver = new HandlerClassResolver(
                    new FromClassNameResolver(),
                    new MethodWithShortObjectNameResolver('Query'),
                    new InMemoryLocator()
                )
            )
            ->and($middleware = new QueryHandlerMiddleware($resolver))
            ->and($query = new NearByVenuesQuery($this->faker->latitude(), $this->faker->longitude()))
            ->and($queryHandler = new VenuesQueryHandler())
            ->and($resolver->addHandler(NearByVenuesQuery::class, $queryHandler))
            ->and($callable = function (array $result) {
                return json_encode($result);
            })
            ->when($result = $middleware->handle($query, $callable))
            ->then()
                ->string($result)
                    ->isNotEmpty()
                    ->isEqualTo(json_encode($queryHandler->nearbyVenues($query)))
                ->exception(function () use ($middleware, $callable) {
                    $middleware->handle(new \StdClass(), $callable);
                })->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /**
     * Test dispatcher method.
     */
    public function testDispatcher()
    {
        $this
            ->given(
                $resolver = new HandlerClassResolver(
                    new FromClassNameResolver(),
                    new MethodWithShortObjectNameResolver('Query'),
                    new InMemoryLocator([NearByVenuesQuery::class => new VenuesQueryHandler()])
                )
            )
            ->and($middleware = new QueryHandlerMiddleware($resolver))
            ->when($result = $middleware->resolver())
            ->then()
                ->object($result)
                    ->isEqualTo($resolver)
        ;
    }
}