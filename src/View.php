<?php

namespace Danidoble\SkeletonConfig;

use Danidoble\SkeletonConfig\Config\Blade;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class View
 */
class View
{
    /**
     * @var string
     */
    protected string $view = 'index';
    /**
     * @var array
     */
    protected array $data = [];
    /**
     * @var int
     */
    protected int $status = 200;
    /**
     * @var array
     */
    protected array $mergeData = [];
    /**
     * @var bool
     */
    protected bool $isComponent = false;
    /**
     * @var string
     */
    protected string $component_dir = '';

    /**
     * @param string $view
     * @param array $data
     * @param int $status
     * @param array $mergeData
     */
    public function __construct(string $view, array $data = [], int $status = 200, array $mergeData = [])
    {
        $this->view = $view;
        $this->data = $data;
        $this->status = $status;
        $this->mergeData = $mergeData;
    }

    /**
     * @param $key
     * @return null
     */
    public function __get($key)
    {
        if (isset($this->$key)) {
            return $this->$key;
        }
        return null;
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->$key);
    }

    /**
     * @param $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->$key);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return string
     */
    public function __invoke(): string
    {
        return $this->render();
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $data = array_merge($this->data, $this->mergeData);
        if ($this->isComponent) {
            return Blade::$blade->make($this->component_dir . '.' . $this->view)->with($data)->render();
        }
        return Blade::$blade->make($this->view)->with($data)->render();
    }

    /**
     * @return Response
     */
    public function toResponse(): Response
    {
        return new Response($this->render(), $this->status);
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function set(string $key, $value): View
    {
        $this->{$key} = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return null
     */
    public function get(string $key)
    {
        return $this->{$key} ?? null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->{$key});
    }

    /**
     * @param string $key
     * @return $this
     */
    public function remove(string $key): View
    {
        unset($this->{$key});
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function merge(array $data): View
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }
}