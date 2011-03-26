<?php

namespace FOQ\TyperBundle;

/**
 * Trims code
 */
class CodeTrimmer
{
    /**
     * The code to trim
     *
     * @var string
     */
    protected $code = null;

    /**
     * Instanciates a new CodeTrimmer
     *
     * @param string code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Trim the code
     *
     * @return string
     **/
    public function trim()
    {
        $loc = explode("\n", $this->code);

        foreach ($loc as $i => $l) {
            $loc[$i] = rtrim($l);
        }

        $code = implode("\n", $loc);

        return $code;
    }

}
