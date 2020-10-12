<?php


namespace App\Command;


use App\Controller\JiraController;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMailIssuesCommand extends Command
{
    protected static $defaultName = 'app:send-issues';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var JiraController
     */
    private $jiraController;
    /**
     * @var Swift_Mailer
     */
    private $mailer;


    /**
     * CreateSoldeCongeCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param JiraController $jiraController
     *  @param Swift_Mailer $mailer
     */
    public function __construct(EntityManagerInterface $entityManager, JiraController $jiraController, Swift_Mailer $mailer)
    {
        parent::__construct();

        $this->entityManager = $entityManager;

        $this->jiraController = $jiraController;

        $this->mailer = $mailer;
    }
    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('send Mail.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Cette commande permet d\'envoyer un mail contenant les nouveaux tickets');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('le mail est envoyé');
        return $this->jiraController->postSendMail($this->mailer);
    }

}
