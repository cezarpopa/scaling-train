<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RandomSpellCommand extends Command
{
    protected static $defaultName = 'app:random-spell';
    protected static $defaultDescription = 'Cast a random spell';

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('your-name', InputArgument::OPTIONAL, 'Your name')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Yell?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $yourName = $input->getArgument('your-name');

        if ($yourName) {
            $io->note(sprintf('Hi: %s', 'your-name'));
        }


        $spells = [
            'alohomora',
            'confundo',
            'engorgio',
            'expecto patronum',
            'expelliarmus',
            'impedimenta',
            'reparo',
        ];

        $spell = $spells[array_rand($spells)];

        if ($input->getOption('yell')) {
            $spell = strtoupper($spell);
        }

        $io->success($spell);

        return 0;
    }
}
