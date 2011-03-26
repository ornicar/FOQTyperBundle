<?php

namespace FOQ\TyperBundle;

/**
 * Contains the informations needed to build a class
 *
 * @author     Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ClassDefinition
{
    protected $config;

    /**
     * Instanciates a configuration
     **/
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getFile()
    {
        return $this->config['file'];
    }

    public function getType()
    {
        return isset($this->config['type']) ? $this->config['type'] : 'class';
    }

    public function getName()
    {
        return isset($this->config['name']) ? $this->config['name'] : strpos(basename($this->getFile()), -4);
    }

    public function getClassName()
    {
        return $this->extractClassName($this->getName());
    }

    public function getNamespace()
    {
        return $this->extractNamespace($this->getName());
    }

    public function getComment()
    {
        return isset($this->config['comment']) ? $this->config['comment'] : null;
    }

    public function getExtends()
    {
        return isset($this->config['extends']) ? $this->config['extends'] : null;
    }

    public function getImplements()
    {
        return isset($this->config['implements']) ? $this->config['implements'] : array();
    }

    public function getProperties()
    {
        $properties = array();
        $propertiesConfig = isset($this->config['properties']) ? $this->config['properties'] : array();
        foreach ($propertiesConfig as $name => $propertyConfig) {
            $properties[] = new PropertyDefinition(array_merge(array(
                'name' => $name
            ), $propertyConfig));
        }

        return $properties;
    }

    public function getPropertiesInjectedInConstructor()
    {
        $properties = array();
        foreach ($this->getProperties() as $property) {
            if ($property->isInjectedInConstructor()) {
                $properties[] = $property;
            }
        }

        return $properties;
    }

    public function getUses()
    {
        $uses = array();
        if ($extends = $this->getExtends()) {
            if ($this->extractNamespace($extends) != $this->getNamespace()) {
                $uses[] = $extends;
            }
        }
        foreach ($this->getImplements() as $implement) {
            if ($this->extractNamespace($implement) != $this->getNamespace()) {
                $uses[] = $implement;
            }
        }
        foreach ($this->getProperties() as $property) {
            if ($property->getNamespace() && $property->getNamespace() != $this->getNamespace()) {
                $uses[] = $property->getNamespace();
            }
        }

        return array_unique($uses);
    }

    public function extractNamespace($class)
    {
        if ('\\' == $class[0]) {
            $class = substr($class, 1);
        }
        // namespaced class
        if (false !== $pos = strrpos($class, '\\')) {
            return substr($class, 0, $pos);
        }
    }

    public function extractClassName($class)
    {
        if ('\\' == $class[0]) {
            $class = substr($class, 1);
        }
        // namespaced class
        if (false !== $pos = strrpos($class, '\\')) {
            return substr($class, $pos + 1);
        }

        return $class;
    }
}
