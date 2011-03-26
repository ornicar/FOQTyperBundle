<?php

namespace FOQ\TyperBundle;


/**
 * Writes a class header
 */
class HeaderTyper
{
    /**
     * @var string
     */
    protected $namespace = null;

    /**
     * @var array
     */
    protected $uses = 'array()';

    /**
     * @param string namespace
     * @param array uses
     */
    public function __construct($namespace, array $uses)
    {
        $this->namespace = $namespace;
        $this->uses = $uses;
    }

    public function write()
    {
        $loc = array('<?php', '');

        if ($this->namespace) {
            $loc[] = sprintf('namespace %s;', $this->namespace);
            $loc[] = '';
        }

        foreach ($this->uses as $use) {
            $loc[] = sprintf('use %s;', $use);
        }

        $header = implode("\n", $loc);

        return $header;
    }

}
