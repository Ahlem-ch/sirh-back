<?php


namespace App\Command;


use App\Controller\JiraController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateBaseWithRessourcesCommand extends Command
{
    protected static $defaultName = 'app:update-base-ressources';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var JiraController
     */
    private $jiraController;


    /**
     * CreateSoldeCongeCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param JiraController $jiraController
     */
    public function __construct(EntityManagerInterface $entityManager, JiraController $jiraController)
    {
        parent::__construct();

        $this->entityManager = $entityManager;

        $this->jiraController = $jiraController;
    }
    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('update ressources.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Cette commande permet de mettre à jours les ressources dans la base');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('les ressources sont mis à jour');
        return $this->jiraController->updateRessources();
    }

}
