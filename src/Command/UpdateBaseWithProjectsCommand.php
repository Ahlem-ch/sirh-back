<?php


namespace App\Command;


use App\Controller\ProjetController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateBaseWithProjectsCommand extends Command
{
    protected static $defaultName = 'app:update-base-project';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ProjetController
     */
    private $projetController;


    /**
     * CreateSoldeCongeCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param ProjetController $projetController
     */
    public function __construct(EntityManagerInterface $entityManager, ProjetController $projetController)
    {
        $this->entityManager = $entityManager;
        $this->projetController = $projetController;
        parent::__construct();

    }
    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('update project.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Cette commande permet de mettre à jours les projets dans la base');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('les projets sont mis à jour');
        return $this->projetController->updateBaseProjectAction();
    }

}
