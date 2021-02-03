<?php


namespace App\Command;


use App\Controller\JiraController;
use App\Controller\RegistrationController;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMailAnniversaireCommand extends Command
{
    protected static $defaultName = 'app:mail-anniversaire';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var RegistrationController
     */
    private $registrationController;


    /**
     * CreateSoldeCongeCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param RegistrationController $registrationController
     * @param Swift_Mailer $mailer
     */
    public function __construct(EntityManagerInterface $entityManager, RegistrationController $registrationController, Swift_Mailer $mailer)
    {
        parent::__construct();

        $this->entityManager = $entityManager;

        $this->registrationController = $registrationController;

        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('send Mail.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Cette commande permet d\'envoyer un mail pour un nouvel anniversaire');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('le mail est envoyé');
        return $this->registrationController->getAnniversaireMailAction($this->mailer);
    }

}
