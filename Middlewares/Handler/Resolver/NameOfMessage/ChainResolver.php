<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage;

use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Collections\ArrayCollection;
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Exception\InvalidResolverException;

/**
 * ChainResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class ChainResolver implements ResolverInterface
{
    /**
     * @var ArrayCollection
     */
    protected $resolvers;

    /**
     * ChainResolver constructor.
     *
     * @param array $resolvers
     */
    public function __construct(array $resolvers)
    {
        $this->resolvers = new ArrayCollection();
        foreach ($resolvers as $resolver) {
            $this->ensureResolver($resolver);
            $this->resolvers->add($resolver);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message)
    {
        foreach ($this->resolvers as $resolver) {
            try {
                /* @var ResolverInterface $resolver */
                return $resolver->resolve($message);
            } catch (\Exception $exception) {
            }
        }

        throw $this->notFoundException($message);
    }

    /**
     * @param $resolver
     *
     * @throws InvalidResolverException
     */
    abstract protected function ensureResolver($resolver);

    /**
     * @param MessageInterface $message
     *
     * @return NotFoundException
     */
    abstract protected function notFoundException($message);
}