<?php
namespace Chiave\ErepublikScrobblerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ErepublikScrobblerCitizenCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('erepublik:scrobbler:citizen')
            ->setDescription('Scrobble citizen from erepublik\'s pages.')
            ->addArgument(
                'citizenId',
                InputArgument::REQUIRED,
                'Id of citizen.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cScrobbler = $this->getContainer()->get('erepublik_citizen_scrobbler');

        $cScrobbler->updateCitizen($input->getArgument('citizenId'));

        $output->writeln('Done. I think.');
    }
}
