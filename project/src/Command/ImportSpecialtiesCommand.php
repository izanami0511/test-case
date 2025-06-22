<?php

namespace App\Command;

use App\Service\SpecialtySyncService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-specialties',
    description: 'Add a short description for your command',
)]
//0 5 * * * /../project php bin/console app:sync-specialties крон команда для сервера
class ImportSpecialtiesCommand extends Command
{
    public const JSON_URL = 'https://gitlab.grokhotov.ru/hr/symfony-test-vacancy/-/raw/new-test-case/specialities.json';

    public function __construct(
        private readonly SpecialtySyncService $syncService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Sync specialties from external JSON');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $count = $this->syncService->sync(self::JSON_URL);
            $io->success(sprintf('Successfully synced specialties. New: %d', $count));
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Error during sync: '.$e->getMessage());
            return Command::FAILURE;
        }
    }
}
