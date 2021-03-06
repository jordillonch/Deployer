<?php

namespace JordiLlonch\Component\Deployer\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

class Code2ProductionCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('deployer:code2production')
            ->setDescription('Move downloaded code to production environment on configured servers.')
            ->setHelp(<<<EOT
The <info>deployer:code2production</info> command move downloaded code to production environment on all configured servers and clears APC cache.
If a problem is detected during the execution it will be rolled back.
It is the most important command.
EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($input->isInteractive()) {
            $dialog = $this->getHelperSet()->get('dialog');
            $confirmation = $dialog->askConfirmation($output, '<question>WARNING! You are about to put code to production. Are you sure you wish to continue? (y/n)</question>', false);
        }
        else $confirmation = true;

        if ($confirmation === true) {
            $this->deployer->runCode2Production();
        } else {
            $output->writeln('<error>Code to production cancelled!</error>');
        }
    }
}