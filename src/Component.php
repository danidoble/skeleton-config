<?php

namespace Danidoble\SkeletonConfig;

class Component extends View
{
    /**
     * @var bool
     */
    protected bool $isComponent = true;

    /**
     * @var string
     */
    protected string $component_dir = 'components';

    /**
     * @var string|null
     */
    protected ?string $slug = null;
    /**
     * @var string|null
     */
    protected ?string $css_class = null;
    /**
     * @var string|null
     */
    protected ?string $data_elements = null;

    /**
     * @param string $place
     * @return $this
     */
    public function setDir(string $place): Component
    {
        $this->component_dir = $place;
        return $this;
    }

    /**
     * @return string
     */
    public function getDir(): string
    {
        return $this->component_dir;
    }

    /**
     * @return $this
     */
    public function removeDir(): Component
    {
        $this->component_dir = '';
        return $this;
    }

    /**
     * @param string|null $slug
     * @return $this
     */
    public function slug(?string $slug): Component
    {
        $this->view = $slug;
        return $this;
    }

    /**
     * @param string|null $class
     * @return $this
     */
    public function css(?string $class): Component
    {
        $this->css_class = $class;
        return $this;
    }

    /**
     * @param string|null $elements
     * @return $this
     */
    public function elements(?string $elements): Component
    {
        $this->data_elements = $elements;
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $this->merge([
            'slug' => $this->slug,
            'css' => $this->css_class,
            'elements' => $this->data_elements,
        ]);
        return parent::render();
    }

}