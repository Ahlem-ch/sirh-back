<?php


namespace App\Command;


use App\Entity\Poste;
use App\Repository\ConfigAutorisationRepository;
use App\Repository\CongeRepository;
use App\Repository\PosteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSoldeAutorisationCommand extends Command
{
    protected static $defaultName = 'app:add-pay';

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ConfigAutorisationRepository
     */
    private $configAutorisationRepository;


    /**
     * CreateSoldeCongeCommand constructor.
     * @param UserRepository $userRepository
     * @param ConfigAutorisationRepository $configAutorisationRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct( UserRepository $userRepository, ConfigAutorisationRepository $configAutorisationRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->configAutorisationRepository = $configAutorisationRepository;
        parent::__construct();

    }
    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('add solde.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Cette commande permet de mettre à jours les soldes d\'autorisataion de sortie...');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $data = $this->userRepository->findAll();
        $config_autorisation = $this->configAutorisationRepository->findOneBy([]);

        $list_id = [];
        foreach ($data as $d) {
            array_push($list_id, $d->getId());
        }

        foreach ($list_id as $id) {
            $emp = $this->userRepository->findOneBy(['id' => $id]);
            $emp->setSoldeAutorisationSortie( $config_autorisation->getNbAutorisation());
            $this->entityManager->persist($emp);
            $this->entityManager->flush();
        }

        $output->writeln('les soldes autorisations de sortie sont à jour');
        return 0;
    }

}
