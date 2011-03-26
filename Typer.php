<?php

namespace FOQ\TyperBundle;

use Zend\CodeGenerator\Php\PhpFile;
use Zend\CodeGenerator\Php\PhpClass;
use Zend\CodeGenerator\Php\PhpProperty;
use Zend\CodeGenerator\Php\PhpDocblock;
use Zend\CodeGenerator\Php\PhpMethod;
use Zend\CodeGenerator\Php\PhpParameter;

/**
 * Writes the class code
 *
 * @author     Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class Typer
{
    protected $classDefinition;

    public function __construct(ClassDefinition $classDefinition)
    {
        $this->classDefinition = $classDefinition;
    }

    public function write()
    {
        $class = new PhpClass();
        $class->setName($this->classDefinition->getName());
        if ($extends = $this->classDefinition->getExtends()) {
            $class->setExtendedClass($this->classDefinition->extractClassName($extends));
        }
        $implements = $extends = $this->classDefinition->getImplements();
        $implementedInterfaces = array();
        foreach ($this->classDefinition->getImplements() as $implement) {
            $implementedInterfaces[] = $this->classDefinition->extractClassName($implement);
        }
        $class->setImplementedInterfaces($implementedInterfaces);

        if ($comment = $this->classDefinition->getComment()) {
            $class->setDocblock(new PhpDocblock(array(
                'shortDescription' => $this->classDefinition->getComment()
            )));
        }

        $constructorProperties = $this->classDefinition->getPropertiesInjectedInConstructor();
        if (!empty($constructorProperties)) {
            $method = new PhpMethod();
            $method->setName('__construct');
            foreach ($constructorProperties as $propertyDefinition) {
                $parameter = new PhpParameter();
                $parameter->setName($propertyDefinition->getName());
                if (!$this->isNativeType($propertyDefinition->getType())) {
                    $parameter->setType($propertyDefinition->getType());
                }
                $method->setParameter($parameter);
            }
            $body = array();
            foreach ($constructorProperties as $propertyDefinition) {
                $body[] = sprintf('$this->%s = $%s;', $propertyDefinition->getName(), $propertyDefinition->getName());
            }
            $method->setBody(implode("\n", $body));
            $docBlock = new PhpDocblock();
            if ($comment = $propertyDefinition->getComment()) {
                $docBlock->setShortDescription('Instanciates a new '.$this->classDefinition->getClassName());
            }
            foreach ($constructorProperties as $propertyDefinition) {
                $docBlock->setTag(array(
                    'name' => '@param',
                    'description' => $propertyDefinition->getType().' '.$propertyDefinition->getName()
                ));
            }
            $method->setDocblock($docBlock);
            $class->setMethod($method);
        }

        foreach ($this->classDefinition->getProperties() as $propertyDefinition) {
            $property = new PhpProperty();
            $property->setName($propertyDefinition->getName());
            $property->setVisibility($propertyDefinition->getVisibility());
            $property->setDefaultValue($propertyDefinition->getDefault());
            $docBlock = new PhpDocblock();
            if ($comment = $propertyDefinition->getComment()) {
                $docBlock->setShortDescription($comment);
            }
            $docBlock->setTag(array(
                'name' => '@var',
                'description' => $propertyDefinition->getType()
            ));
            $property->setDocblock($docBlock);

            $class->setProperty($property);

            if ($propertyDefinition->hasGetter()) {
                $method = new PhpMethod();
                $method->setName('get'.ucfirst($propertyDefinition->getName()));
                $method->setBody(sprintf('return $this->%s;', $propertyDefinition->getName()));
                $docBlock = new PhpDocblock();
                if ($comment = $propertyDefinition->getComment()) {
                    $docBlock->setShortDescription('Gets: '.$comment);
                }
                $docBlock->setTag(array(
                    'name' => '@return',
                    'description' => $propertyDefinition->getType().' '.$propertyDefinition->getName()
                ));
                $method->setDocblock($docBlock);
                $class->setMethod($method);
            }

            if ($propertyDefinition->hasSetter()) {
                $method = new PhpMethod();
                $method->setName('set'.ucfirst($propertyDefinition->getName()));
                $parameter = new PhpParameter();
                $parameter->setName($propertyDefinition->getName());
                if (!$this->isNativeType($propertyDefinition->getType())) {
                    $parameter->setType($propertyDefinition->getType());
                }
                $method->setParameter($parameter);
                $method->setBody(sprintf('$this->%s = $%s;', $propertyDefinition->getName(), $propertyDefinition->getName()));
                $docBlock = new PhpDocblock();
                if ($comment = $propertyDefinition->getComment()) {
                    $docBlock->setShortDescription('Sets: '.$comment);
                }
                $docBlock->setTag(array(
                    'name' => '@param',
                    'description' => $propertyDefinition->getType().' '.$propertyDefinition->getName()
                ));
                $method->setDocblock($docBlock);
                $class->setMethod($method);
            }
        }

        $code = $class->generate();
        $locs = explode("\n", $code);

        // remove header
        $code = substr($code, strpos($code, "\n") +1);
        $code = substr($code, strpos($code, "\n") +1);

        // add header
        $headerWriter = new HeaderTyper($this->classDefinition->getNamespace(), $this->classDefinition->getUses());
        $code = $headerWriter->write()."\n".$code;

        // trim lines
        $codeTrimmer = new CodeTrimmer($code);
        $code = $codeTrimmer->trim($code);

        return $code;
    }

    /**
     * Tells whether a given type is a native PHP type or not
     *
     * @return null
     **/
    public function isNativeType($type)
    {
        return in_array(strtolower($type), array('int', 'string', 'bool', 'boolean', 'array'));
    }
}
