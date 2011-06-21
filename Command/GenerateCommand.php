<?php

namespace FOQ\TyperBundle\Command;

use Symfony\Component\Console\Input;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

use InvalidArgumentException;

/**
 * Generate a class or interface
 */
class GenerateCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('file', InputArgument::REQUIRED, 'File containing the class configuration'),
            ))
            ->setName('typer:generate')
        ;
    }

    /**
     * @see Command
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = $input->getArgument('file');
        if (!file_exists($configFile)) {
            throw new InvalidArgumentException('Invalid config file specified');
        }
        $generator = $this->getContainer()->get('foq_typer.generator');
        $generator->generateFile($configFile);
    }
}
