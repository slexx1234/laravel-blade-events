<?php

namespace Slexx\LaravelBladeEvents;

class Event
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * @var bool
     */
    protected $isStopped = false;

    /**
     * BladeEvent constructor.
     * @param string $name
     * @param array $arguments
     */
    public function __construct($name, $arguments)
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Is stopped event propagation?
     * @return bool
     */
    public function isStopped()
    {
        return $this->isStopped;
    }

    /**
     * Stop event propagation
     */
    public function stop()
    {
        $this->isStopped = true;
    }
}