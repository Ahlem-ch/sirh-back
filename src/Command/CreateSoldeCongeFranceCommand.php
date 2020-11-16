<?php


namespace App\Command;

use App\Repository\ConfigSoldeCongeFranceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSoldeCongeFranceCommand extends Command
{
    protected static $defaultName = 'app:add-solde-conge-france';

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ConfigSoldeCongeFranceRepository
     */
    private $configSoldeCongeRepository;


    /**
     * CreateSoldeCongeCommand constructor.
     * @param UserRepository $userRepository
     * @param ConfigSoldeCongeFranceRepository $configSoldeCongeRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct( UserRepository $userRepository, ConfigSoldeCongeFranceRepository $configSoldeCongeRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->configSoldeCongeRepository = $configSoldeCongeRepository;

        parent::__construct();

    }
    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('add solde France.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Cette commande permet de mettre à jours les soldes de congé...');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $data = $this->userRepository->findAll();
        $config_conge = $this->configSoldeCongeRepository->findOneBy([]);

        $list_id = [];
        foreach ($data as $d) {
            if($d->getLocalisation() === 'France') {
                array_push($list_id, $d->getId());
            }
        }

        foreach ($list_id as $id) {
            $emp = $this->userRepository->findOneBy(['id' => $id]);
            $emp->setSolde($emp->getSolde() + $config_conge->getSolde());
            $this->entityManager->persist($emp);
            $this->entityManager->flush();
        }

        $output->writeln('les soldes de congé sont à jour');
        return 0;
    }

}
