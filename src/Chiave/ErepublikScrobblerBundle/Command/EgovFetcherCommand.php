<?php
namespace Chiave\ErepublikScrobblerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EgovFetcherCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('egov:fetcher')
            ->setDescription('Fetch data from eGov.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $muFetcher = $this->getContainer()->get('egov_nationalraport_fetcher');

        $muFetcher->updateMilitaryUnits();
        $muFetcher->updateCitizens();

        $output->writeln('Done. I think.');
    }
}
