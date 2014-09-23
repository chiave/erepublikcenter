<?php
namespace Chiave\ErepublikScrobblerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ErepublikScrobblerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('erepublik:scrobbler')
            ->setDescription('Scrobble erepubliks data.')
            // ->addArgument(
            //     'citizenId',
            //     InputArgument::REQUIRED,
            //     'Id of citizen.'
            // )
            // ->addArgument(
            //     'master_id',
            //     InputArgument::REQUIRED,
            //     'Master Id of entity'
            // )
            // ->addArgument(
            //     'slave_id',
            //     InputArgument::REQUIRED,
            //     'Slave Id of entity?'
            // )
            // ->addOption(
            //    'inception',
            //    null,
            //    InputOption::VALUE_NONE,
            //    'If set, the task will yell in uppercase letters'
            // )
            // ->addOption(
            //    'check',
            //    null,
            //    InputOption::VALUE_NONE,
            //    'Print information abour relation between entities'
            // )
            // ->addOption(
            //    'remove',
            //    null,
            //    InputOption::VALUE_NONE,
            //    'Remove relation between entities'
            // )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    // $output->writeln('<info>foo</info>');            // green text
    // $output->writeln('<comment>foo</comment>');      // yellow text
    // $output->writeln('<question>foo</question>');    // black text on a cyan background
    // $output->writeln('<error>foo</error>');          // white text on a red background

        $cScrobbler = $this->getContainer()->get('erepublik_citizen_scrobbler');
        // $users = $this->getContainer()
        //     ->get('doctrine_mongodb')
        //     ->getRepository('ChiaveUserBundle:User')
        //     ->findAll()
        // ;

        // foreach($users as $user) {
        //     $cScrobbler->updateCitizen($user);
        // }

        $cScrobbler->updateCitizens();
        $output->writeln('Done. I think.');
    }
}
