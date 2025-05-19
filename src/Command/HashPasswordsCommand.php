<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:hash-passwords',
    description: 'Hash all plaintext passwords in the database',
)]
class HashPasswordsCommand extends Command
{
    private $em;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $users = $this->em->getRepository(User::class)->findAll();
        $count = 0;
        
        foreach ($users as $user) {
            if ($user->getPassword() === 'plainpassword') {
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $user,
                    'admin123'  // Mot de passe désiré
                );
                $user->setPassword($hashedPassword);
                $count++;
            }
        }
        
        $this->em->flush();
        
        $io->success("Hashed passwords for $count users.");
        
        return Command::SUCCESS;
    }
}