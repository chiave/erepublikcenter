<?php
namespace Chiave\StatsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RankingUpdaterCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ranking:updater')
            ->setDescription('Update ranking.')
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

        $this->getContainer()
            ->get('ranking')
            ->updateRankingUsers();

        $output->writeln('Done. I think.');
    }
}
