<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Classe\Search;
use App\Form\UserType;
use App\Entity\Partner;
use App\Form\SearchType;
use App\Form\PartnerType;
use App\Entity\Permission;
use App\Form\UserShowType;
use App\Form\PermissionType;
use App\Form\PartnerFormType;
use App\Form\PartnerFormShowType;
use App\Repository\UserRepository;
use App\Repository\PartnerRepository;
use App\Repository\PermissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/partner')]
#[IsGranted('ROLE_ADMIN')]
class PartnerController extends AbstractController
{
    public function __construct(
        public EntityManagerInterface $entityManager
    ) {
    }


    // INDEX FOR ALL PARTNERS IN DB
    #[Route('/', name: 'app_partner_index', methods: ['GET'])]
    public function index(Request $request, PartnerRepository $partnerRepository, PermissionRepository $permissionRepository): Response
    {
        $partners = $partnerRepository->findAll();

        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partners = $this->entityManager->getRepository(Partner::class)->findWithSearch($search);
        }

        // Si la requête est une requête AJAX
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'content' => $this->renderView('partner/_partners.html.twig', ['partners' => $partners])
            ]);
        }

        return $this->render('partner/index.html.twig', [
            'partners' => $partners,
            'permissions' => $permissionRepository->findAll(),
            'form' => $form->createView()
        ]);
    }

    // CREATE A NEW PARTNER
    #[Route('/new', name: 'app_partner_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, PartnerRepository $partnerRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Notification email
        $notification = null;

        $user = new User(); // J'instancie ma classe User()
        $partner = new Partner(); // J'instancie ma classe Partner()
        $permissions = new Permission();

        $form = $this->createForm(PartnerType::class); // Mon formulaire PartnerType

        $form->handleRequest($request); // Écoute la requête entrante

        if ($form->isSubmitted() && $form->isValid()) {

            // Injecte dans mon objet User() toutes les données qui sont récupérées du formulaire
            $user = $form->getData();

            // Vérifier que mon partner n'est pas déjà présent en BDD
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email) {
                // J'utilise UserPasswordHasherInterface pour encoder le mot de passe
                $password = $passwordHasher->hashPassword($user, $user->getPassword());
                // Je réinjecte $password qui est crypté dans l'objet User()
                $user->setPassword($password);

                // Je définis que le partenaire de mon User est $partner
                $user->setPartner($partner);
                $partner->setUser($user);

                // Je récupère les données "non mappées" du formulaire UserType et les injecte dans mon instance de Partner.
                $partner->setName($form->get('partnerName')->getData());

                // Je définis que la nouvelle donnée aura pas défaut le ['ROLE_PARTENAIRE]
                $user->setRoles(['ROLE_PARTENAIRE']);

                // Je récupère les données non mappées du formulaire et les injecte dans mon objet  Permissions
                $permissions->setIsNewsletter($form->get('isNewsletter')->getData());
                $permissions->setIsVenteBoisson($form->get('isVenteBoisson')->getData());
                $permissions->setIsOffreSac($form->get('isOffreSac')->getData());
                $permissions->setIsBadgePerso($form->get('isBadgePerso')->getData());
                $permissions->setIsVenteMerch($form->get('isVenteMerch')->getData());

                // Je déclare que mon partenaire a de nouvelles permissions et que cet objet    permissions a un nouveau partenaire
                $partner->addPermission($permissions);
                $permissions->addPartner($partner);

                $userRepository->add($user, true);
                $partnerRepository->add($partner, true);

                $this->addFlash(
                    'success',
                    'Le partenaire "' . $partner->getName() . '" a été ajouté avec succès'
                );

                //**** ENVOI DU  MAIL DE CONFIRMATION de création de Partenaire ****\\\
                $resetPasswordUrl = $this->generateUrl('app_reset_password');
                $mail = new Mail();

                $content = "Bonjour " . $user->getName() . "<br/><br/>Vous disposez désormais d'un compte PARTENAIRE pour votre établissement " . $partner->getName() . ", et d'un accès en lecture seule au panel d'administration de STUDI SPORT.<br/><br/> Vous pourrez y découvrir vos STRUCTURES (clubs) rattachées à votre établissement.<br/><br/>";

                $content .= "<hr>";
                $content .= "<h3>Vos informations de connexion :";
                $content .= "<h5>Email de connexion : " . $user->getEmail();
                $content .= "<h5>Nom de l'utilisateur : " . $user->getName();
                $content .= "<h5>Nom de votre franchise : " . $partner->getName();
                $content .= "<hr>";
                $content .= "<h3>Vos fonctionnalités activées :";

                // Envoi des permissions
                if ($permissions->isIsNewsletter() == true) {
                    $content .=  "<h5>Newsletter : OK";
                }
                if ($permissions->isIsVenteBoisson() == true) {
                    $content .=  "<h5>Vente de Boisson : OK";
                }
                if ($permissions->isIsOffreSac() == true) {
                    $content .=  "<h5>Sac offert : OK";
                }
                if ($permissions->isIsBadgePerso() == true) {
                    $content .=  "<h5>Badge Personnalisé : OK";
                }
                if ($permissions->isIsVenteMerch() == true) {
                    $content .=  "<h5>Vente de Merchandising : OK";
                }

                $content .= "<hr> <br/><br/><br/>";
                $content .= "Votre email de connexion est " . $user->getEmail() . ".<br><br>";
                $content .= "<h3>Pour des raisons de sécurité, vous devez demander à redéfinir votre mot de passe en <a href='#" . $resetPasswordUrl . "'> CLIQUANT ICI </a>. Une fois la demande de réinitialisation effectuée, vous disposerez de 3 heures pour le modifier.</h3><br/><br/><br/>";

                $content .= "A très bientôt chez STUDI SPORT !";

                $mail->send($user->getEmail(), $user->getName(), 'Vous avez un nouveau compte PARTENAIRE !', $content);
                // ***************************************************************** \\\


                return $this->redirectToRoute('app_partner_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $notification = 'Cet email est déjà enregistré.';
            }
        }

        return $this->renderForm('partner/_new.html.twig', [
            'user' => $user,
            'partner' => $partner,
            'form' => $form,
            'notification' => $notification
        ]);
    }

    // EDIT A PARTNER
    #[Route('/edit/{id}', name: 'app_partner_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, PartnerRepository $partnerRepository, ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        $partner = $partnerRepository->findOneBy(['id' => $id]); // Catch le partner qui a l'id ciblée
        $partnerUser = $partner->getUser(); // Catch l'utilisateur relié à ce partner

        // Récupérer les permissions du partenaire
        $permArray = ($partner->getPermissions()->getValues()); // Ici, on a un Persistent Collection. Je le transforme en array pour pouvoir le parcourir.
        foreach ($permArray as $p) {
            $permId = $p->getId(); // Je récupère l'id de cet objet permission rattaché à l'user.
        }

        $userPermissions = $doctrine->getRepository(Permission::class)->find($permId); // De cette façon, j'ai récupéré mon objet Entity\Permissions


        $items = ['user' => $partnerUser, 'partner' => $partner, 'permissions' => $userPermissions]; // Tableau regroupant les 2 entités

        $form = $this->createFormBuilder($items) // Formulaire regroupant les 3 entités User, Partner, et Permissions
            ->add('user', UserType::class, [
                'isEdit' => true,
            ])
            ->add('partner', PartnerFormType::class)
            ->add('permissions', PermissionType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userPermissions->addPartner($partner);

            $entityManager->persist($userPermissions);
            $entityManager->persist($partnerUser);
            $entityManager->persist($partner);
            $entityManager->flush();


            $this->addFlash(
                'success',
                'Le partenaire "' . $partner->getName() . '" a été modifié avec succès'
            );

            //**** ENVOI DU MAIL DE MODIFICATION de PARTENAIRE ****\\\
            $mail = new Mail();

            $content = "Bonjour " . $partnerUser->getName() . "<br/><br/>";
            $content .= "Suite à votre demande, les informations de votre partenaire " . $partner->getName() . " ont été mises à jour par un administrateur STUDI SPORT. Vous les retrouverez ci-dessous :<br/><br/>";
            $content .= "<hr>";
            $content .= "<h3>Vos informations de connexion :";
            $content .= "<h5>Email de connexion : " . $partnerUser->getEmail();
            $content .= "<h5>Nom de l'utilisateur : " . $partnerUser->getName();
            $content .= "<h5>Nom de votre franchise : " . $partner->getName();
            $content .= "<hr>";
            $content .= "<h3>Vos fonctionnalités activées :";

            // Envoi des permissions
            if ($userPermissions->isIsNewsletter() == true) {
                $content .=  "<h5>Newsletter : OK";
            }
            if ($userPermissions->isIsVenteBoisson() == true) {
                $content .=  "<h5>Vente de Boisson : OK";
            }
            if ($userPermissions->isIsOffreSac() == true) {
                $content .=  "<h5>Sac offert : OK";
            }
            if ($userPermissions->isIsBadgePerso() == true) {
                $content .=  "<h5>Badge Personnalisé : OK";
            }
            if ($userPermissions->isIsVenteMerch() == true) {
                $content .=  "<h5>Vente de Merchandising : OK";
            }

            $content .= "<hr>";
            $content .= "Pour toute autre besoin de modification, veuillez contacter <a href='#'> l'administrateur STUDI SPORT </a>";

            $mail->send($partnerUser->getEmail(), $partnerUser->getName(), 'Mise à jour de vos informations et permissions PARTENAIRE', $content);
            // ***************************************************************** \\\

            return $this->redirectToRoute('app_partner_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partner/_edit.html.twig', [
            'partner' => $partner,
            'form' => $form,
            'permissions' => $userPermissions
        ]);
    }

    // SHOW A PARTNER
    #[Route('/show/{id}', name: 'app_partner_show', methods: ['GET'])]
    public function show(int $id, Request $request, PartnerRepository $partnerRepository, ManagerRegistry $doctrine)
    {

        // Récupérer les permissions du partenaire
        $partner = $partnerRepository->findOneBy(['id' => $id]); // Catch le partner qui a l'id ciblée

        $partnerUser = $partner->getUser(); // Catch l'utilisateur relié à ce partner
        $permArray = ($partner->getPermissions()->getValues()); // Ici, on a un Persistent Collection. Je le transforme en array pour pouvoir le parcourir.
        foreach ($permArray as $p) {
            $permId = $p->getId(); // Je récupère l'id de cet objet permission rattaché à l'user.
        }

        $userPermissions = $doctrine->getRepository(Permission::class)->find($permId); // De cette façon, j'ai récupéré mon objet Entity\Permissions


        $partnerStructures = $partner->getStructures()->getValues();


        $items = ['user' => $partnerUser, 'partner' => $partner, 'permissions' => $userPermissions]; // Tableau regroupant les 2 entités

        $form = $this->createFormBuilder($items) // Formulaire regroupant les 2 entités
            ->add('user', UserShowType::class, [
                'isEdit' => true,
            ])
            ->add('partner', PartnerFormShowType::class)
            ->add('permissions', PermissionType::class)
            ->getForm();

        $form->handleRequest($request);

        return $this->renderForm('partner/_show.html.twig', [
            'partner' => $partner,
            'form' => $form,
            'permissions' => $userPermissions,
            'partnerStructures' => $partnerStructures
        ]);
    }

    // DELETE A PARTNER
    #[Route('/delete/{id}', name: 'app_partner_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Partner $partner)
    {

        if (!$partner) {
            $this->addFlash(
                'warning',
                'L\utilisateur n\'a pas été trouvé'
            );
            return $this->render('user/_delete.html.twig', [
                'partner' => $partner,
            ]);
        }

        $manager->remove($partner); //REMOVE
        $manager->flush();


        $this->addFlash(
            'danger',
            'Le partenaire "' . $partner->getName() . '" a été supprimé avec succès'

        );

        return $this->redirectToRoute('app_partner_index', [], Response::HTTP_SEE_OTHER);
    }
}
