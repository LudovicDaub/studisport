<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Partner;
use App\Entity\Structure;
use App\Entity\Permission;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    public function __construct(
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        //?  ADMIN (ADMIN)
        $adminUser = new User();

        $adminUser
            ->setName('Admin')
            ->setEmail('admin@admin.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setIsActive(true)
            ->setPassword($this->passwordHasher->hashPassword($adminUser, ('admin123.')));

        //?  USER 1 (PARTENAIRE)
        $User1 = new User();
        $Partner1 = new Partner();
        $PartnerPermissions = new Permission();

        //?  USER 2 (STRUCTURE)
        $User2 = new User();
        $Structure2 = new Structure();
        $StructurePermissions = new Permission();

        //?  USER 4 (STRUCTURE INACTIVE)
        $User4 = new User();
        $Structure4 = new Structure();
        $Structure4Permissions = new Permission();

        //?  USER 5 (PARTENAIRE)
        $User5 = new User();
        $Partner5 = new Partner();
        $Partner5Permissions = new Permission();

        //?  USER 6 (PARTENAIRE)
        $User6 = new User();
        $Partner6 = new Partner();
        $Partner6Permissions = new Permission();


        // USER 1
        $User1
            ->setName('Directeur Planète Fitness')
            ->setEmail('plafit@partenaire.fr')
            ->setRoles(['ROLE_PARTENAIRE'])
            ->setIsActive(true)
            ->setPassword($this->passwordHasher->hashPassword($User1, ('Pfitness123$')));
        // PARTNER 1 (rattaché à User 1)
        $Partner1
            ->setName('Planète Fitness')
            ->setUser($User1)
            ->addStructure($Structure2)
            ->addPermission($PartnerPermissions);

        // USER 5
        $User5
            ->setName('Directeur Orange Bleue')
            ->setEmail('oranble@partenaire.fr')
            ->setRoles(['ROLE_PARTENAIRE'])
            ->setIsActive(true)
            ->setPassword($this->passwordHasher->hashPassword($User1, ('Obleu123$')));
        // PARTNER 5 (rattaché à User 5)
        $Partner5
            ->setName('Orange Bleue')
            ->setUser($User5)
            ->addStructure($Structure4)
            ->addPermission($Partner5Permissions);

        // USER 6
        $User6
            ->setName('Directeur Orange Jaune')
            ->setEmail('oranjau@partenaire.fr')
            ->setRoles(['ROLE_PARTENAIRE'])
            ->setIsActive(false)
            ->setPassword($this->passwordHasher->hashPassword($User1, ('Ojaune123$')));
        // PARTNER 6 (rattaché à User 6)
        $Partner6
            ->setName('Orange Jaune Saint Lo')
            ->setUser($User6)
            ->addPermission($Partner6Permissions);

        // USER 2
        $User2
            ->setName('Orange bleue - Bayeux')
            ->setEmail('orangebleuebayeux@structure.fr')
            ->setRoles(['ROLE_STRUCTURE'])
            ->setIsActive(true)
            ->setPassword($this->passwordHasher->hashPassword($User2, ('Orange123$')));
        // STRUCTURE 2 (rattaché à User 2)
        $Structure2
            ->setUser($User2)
            ->setPartner($Partner1)
            ->setPostalAdress('17 rue du sport, Bayeux')
            ->addPermission($StructurePermissions);

        // USER 4 INACTIF
        $User4
            ->setName('Orange Bleue - Carpiquet')
            ->setEmail('orangebleuecarpiquet@structure.fr')
            ->setRoles(['ROLE_STRUCTURE'])
            ->setIsActive(false)
            ->setPassword($this->passwordHasher->hashPassword($User1, ('genou123$')));
        // STRUCTURE 4 INACTIVE (rattaché à User 3)
        $Structure4
            ->setUser($User4)
            ->setPartner($Partner5)
            ->setPostalAdress('15 rue des restaurants')
            ->addPermission($Structure4Permissions);

        // PERMISSIONS PARTNER 1
        $PartnerPermissions
            ->setIsBadgePerso('1')
            ->setIsNewsletter('1')
            ->setIsOffreSac('1')
            ->setIsVenteBoisson('0')
            ->setIsVenteMerch('0');

        // PERMISSIONS PARTNER 5
        $Partner5Permissions
            ->setIsBadgePerso('0')
            ->setIsNewsletter('0')
            ->setIsOffreSac('0')
            ->setIsVenteBoisson('1')
            ->setIsVenteMerch('1');

        // PERMISSIONS PARTNER 6
        $Partner6Permissions
            ->setIsBadgePerso('0')
            ->setIsNewsletter('1')
            ->setIsOffreSac('1')
            ->setIsVenteBoisson('0')
            ->setIsVenteMerch('1');

        // PERMISSIONS STRUCTURE 2
        $StructurePermissions
            ->setIsBadgePerso($PartnerPermissions->isIsBadgePerso())
            ->setIsNewsletter($PartnerPermissions->isIsNewsletter())
            ->setIsOffreSac($PartnerPermissions->isIsOffreSac())
            ->setIsVenteBoisson($PartnerPermissions->isIsVenteBoisson())
            ->setIsVenteMerch($PartnerPermissions->isIsVenteMerch());

        // PERMISSIONS STRUCTURE 4
        $Structure4Permissions
            ->setIsBadgePerso($Partner5Permissions->isIsBadgePerso())
            ->setIsNewsletter($Partner5Permissions->isIsNewsletter())
            ->setIsOffreSac($Partner5Permissions->isIsOffreSac())
            ->setIsVenteBoisson($Partner5Permissions->isIsVenteBoisson())
            ->setIsVenteMerch($Partner5Permissions->isIsVenteMerch());

        // PERMISSIONS PARTENAIRE 5
        $Partner5Permissions
            ->setIsBadgePerso($Partner5Permissions->isIsBadgePerso())
            ->setIsNewsletter($Partner5Permissions->isIsNewsletter())
            ->setIsOffreSac($Partner5Permissions->isIsOffreSac())
            ->setIsVenteBoisson($Partner5Permissions->isIsVenteBoisson())
            ->setIsVenteMerch($Partner5Permissions->isIsVenteMerch());

        // PERMISSIONS PARTENAIRE 6
        $Partner6Permissions
            ->setIsBadgePerso($Partner6Permissions->isIsBadgePerso())
            ->setIsNewsletter($Partner6Permissions->isIsNewsletter())
            ->setIsOffreSac($Partner6Permissions->isIsOffreSac())
            ->setIsVenteBoisson($Partner6Permissions->isIsVenteBoisson())
            ->setIsVenteMerch($Partner6Permissions->isIsVenteMerch());

        // Commits
        $manager->persist($adminUser);
        $manager->persist($User1);
        $manager->persist($User2);
        $manager->persist($User4);
        $manager->persist($User5);
        $manager->persist($User6);
        $manager->persist($Partner1);
        $manager->persist($Partner5);
        $manager->persist($Partner6);
        $manager->persist($Structure2);
        $manager->persist($Structure4);
        $manager->persist($PartnerPermissions);
        $manager->persist($Structure4Permissions);
        $manager->persist($Partner5Permissions);
        $manager->persist($Partner6Permissions);

        // Push
        $manager->flush();
    }
}
