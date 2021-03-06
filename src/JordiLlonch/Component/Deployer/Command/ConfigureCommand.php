<?php

namespace JordiLlonch\Component\Deployer\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use JordiLlonch\Bundle\DeployBundle\Service\Configure;

class ConfigureCommand extends BaseCommand
{
    protected function configure()
    {
        $this->addArgument('zone', InputArgument::REQUIRED, 'Zone name. It must exists in jordi_llonch_deploy.zones config.');
        $this->addArgument('action', InputArgument::REQUIRED, 'Action: add, set, rm, list, list_json');
        $this->addArgument('url', InputArgument::OPTIONAL, 'Url/s separated by comma');

        $this
            ->setName('deployer:configure')
            ->setDescription('Configure urls Zones.')
            ->setHelp(<<<EOT
The <info>deployer:configure_zone</info> .
EOT
        );
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $configure = $this->getContainer()->get('jordillonch_deployer.configure');
        $rootDir = $this->getContainer()->get('kernel')->getRootDir();
        $configure->readParametersFile($rootDir . '/config/parameters.yml');

        // Validations
        $zone = $input->getArgument('zone');
        $action = $input->getArgument('action');
        $url = $input->getArgument('url');

        switch($action)
        {
            case 'add' :
                $configure->add($zone, $url);
                break;
            case 'set' :
                $configure->set($zone, $url);
                break;
            case 'rm' :
                $configure->rm($zone, $url);
                break;
            case 'list' :
                $output->writeln($configure->listUrls($zone));
                break;
            case 'list_json' :
                $output->writeln($configure->listUrls($zone, Configure::OUTPUT_JSON));
                break;
            default :
                throw new \Exception('Actions must be: add, set, rm, list or list_json');
        }

        $configure->writeParametersFile();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}