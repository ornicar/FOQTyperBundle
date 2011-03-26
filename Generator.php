<?php

namespace FOQ\TyperBundle;

use Symfony\Component\Yaml\Parser;

/**
 * Generates code for a class or an interface
 *
 * @author     Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class Generator
{
    public function generateFile($file)
    {
        $parser = new Parser();
        $fileContent = file_get_contents($file);
        $configArray = array_merge(array(
            'file' => $file
        ), $parser->parse($fileContent));
        $config = new ClassDefinition($configArray);
        $typer = new Typer($config);
        $code = $typer->write();

        file_put_contents($file, $code);
    }
}
