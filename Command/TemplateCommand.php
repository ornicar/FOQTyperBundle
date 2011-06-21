<?php

namespace FOQ\TyperBundle\Command;

use Symfony\Component\Console\Input;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

/**
 * Generate a class template
 */
class TemplateCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
            ))
            ->setName('typer:template')
        ;
    }

    /**
     * @see Command
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        die(<<<EOF
name:
comment:
extends:
implements: []
properties:
    foo:
        type:
        visibility: protected
        comment:
        default: null
        getter: true
        setter: true
        construct: true
EOF
    );
    }
}
