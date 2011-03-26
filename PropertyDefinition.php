<?php

namespace FOQ\TyperBundle;

/**
 * Contains informations about a class property and its mutators
 *
 * @author     Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class PropertyDefinition
{
    protected $config;

    protected $type;
    protected $namespace;

    public function __construct(array $config)
    {
        $this->config = $config;

        if (isset($config['type'])) {
            $type = $config['type'];
            if ('\\' == $type[0]) {
                $type = substr($type, 1);
            }
            // namespaced type
            if (false !== $pos = strrpos($type, '\\')) {
                $this->namespace = $type;
                $this->type = substr($type, $pos + 1);
            } else {
                $this->type = $type;
            }
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getName()
    {
        return $this->config['name'];
    }

    public function getVisibility()
    {
        return isset($this->config['visibility']) ? $this->config['visibility'] : 'protected';
    }

    public function getComment()
    {
        return isset($this->config['comment']) ? $this->config['comment'] : null;
    }

    public function getDefault()
    {
        return isset($this->config['default']) ? $this->config['default'] : null;
    }

    public function hasGetter()
    {
        return !empty($this->config['getter']);
    }

    public function hasSetter()
    {
        return !empty($this->config['setter']);
    }

    public function isInjectedInConstructor()
    {
        return !empty($this->config['construct']);
    }
}
