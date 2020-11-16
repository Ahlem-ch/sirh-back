<?php


namespace App\Command;


use App\Controller\ContratController;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ContratsSituationMailCommand extends Command
{
    protected static $defaultName = 'app:contrats-situation';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var ContratController
     */
    private $contratController;


    /**
     * CreateSoldeCongeCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param ContratController $contratController
     * @param Swift_Mailer $mailer
     */
    public function __construct(EntityManagerInterface $entityManager, ContratController $contratController, Swift_Mailer $mailer)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->contratController = $contratController;
    }
    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('send mail for contrat situation.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Cette commande permet d\'envoyer un mail contenant le contrat qui va expirer dans un mois ou 3 mois');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('le mail est envoyé');
        return $this->contratController->getContratsSituationAction($this->mailer);
    }

}
