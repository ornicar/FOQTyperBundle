<?php

namespace FOQ\TyperBundle;

use Symfony\Component\Yaml\Parser;

class TyperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getFiles
     *
     * @param string $configFile
     * @param string $codeFile
     */
    public function testAdvanced($configFile, $codeFile)
    {
        $parser = new Parser();
        $fileContent = file_get_contents($configFile);
        $configArray = array_merge(array(
            'file' => $codeFile
        ), $parser->parse($fileContent));
        $config = new ClassDefinition($configArray);
        $typer = new Typer($config);
        $code = $typer->write();
        $expectedCode = file_get_contents($codeFile);

        $this->assertEquals($expectedCode, $code);
    }

    public function getFiles()
    {
        $files = array();
        foreach (array('simple_toaster', 'advanced_toaster') as $name) {
            $files[] = array(
                __DIR__.'/fixtures/'.$name.'.yml',
                __DIR__.'/fixtures/'.$name.'.php'
            );
        }

        return $files;
    }
}
