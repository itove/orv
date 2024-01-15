<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Entity\Node;
use App\Entity\Region;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Feedback;
use App\Entity\Language;
use App\Entity\Conf;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class DashboardController extends AbstractDashboardController
{
    private $doctrine;
    private $nodes;
    private $conf;
    private $regions;

    public function __construct(ManagerRegistry $doctrine)
    {
      $this->doctrine = $doctrine;
      $this->nodes = $doctrine->getRepository(Node::class);
      $this->conf = $doctrine->getRepository(Conf::class)->find(1);
      $this->regions = $doctrine->getRepository(Region::class)->findAll();
    }
    
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator
                    ->setController(NodeCrudController::class)
                    ->set('region', 'news')
                    // ->setAction('detail')
                    // ->setEntityId(1)
                    ->generateUrl()
        );
    }

    public function configureDashboard(): Dashboard
    {
        if ($this->conf) {
            $title = $this->conf->getSitename();
        } else {
            $title = 'Sitename';
        }
        return Dashboard::new()
            ->setLocales(['en', 'zh_CN'])
            // ->renderContentMaximized()
            ->setTitle($title);
    }
    
    public function configureCrud(): Crud
    {
        return Crud::new()
            ->showEntityActionsInlined()
            ->setTimezone('Asia/Shanghai')
            ->setDateTimeFormat('yyyy/MM/dd HH:mm')
            ->setDefaultSort(['id' => 'DESC'])
        ;
    }

    public function configureActions(): Actions
    {
        return Actions::new()
            // ->disable('delete')
            ->add('detail', 'edit')
            ->add('index', 'edit')
            ->add('index', 'new')
            ->add('index', 'delete')
            ->add(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
        ;
    }

    public function configureMenuItems(): iterable
    {
        $regions = $this->doctrine->getRepository(Region::class);
        
        yield MenuItem::linkToUrl('Back to Site', 'fas fa-arrow-circle-left', '/');
        
        yield MenuItem::section('Content Management');
        yield MenuItem::linkToCrud('Product Management', 'fas fa-truck', Node::class)
            ->setQueryParameter('region', 'product')
        ;
        yield MenuItem::linkToCrud('News', 'fas fa-newspaper', Node::class)
            ->setQueryParameter('region', 'news')
        ;
        
        yield MenuItem::section('Static Area');
        
        // admin menu of regions
        foreach ($this->regions as $region) {
            $action = 'index';
            // $action = 'detail';
            if (!in_array($region->getLabel(), ['product', 'news', 'terms', 'service_guarantee'])) {
                yield MenuItem::linkToCrud($region->getName(), "fas fa-{$region->getIcon()}", Node::class)
                    ->setQueryParameter('region', $region->getLabel())
                    ->setAction($action)
                // ->setEntityId(7)
                ;
            }
        }
        
        yield MenuItem::section('Settings');
        yield MenuItem::linkToCrud('Change Password', 'fas fa-key', User::class)
            ->setQueryParameter('action', 'chpw')
            ->setAction('edit')
            ->setEntityId($this->getUser()->getId())
            ;
        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToCrud('Category Management', 'fas fa-list', Category::class);
            yield MenuItem::linkToCrud('Tag Management', 'fas fa-list', Tag::class);
            yield MenuItem::linkToCrud('Feedback', 'fas fa-message', Feedback::class);
            yield MenuItem::linkToCrud('User Management', 'fas fa-users', User::class);
            if ($_ENV['IS_MULTILINGUAL']) {
                yield MenuItem::linkToCrud('Settings', 'fas fa-cog', Conf::class);
            } else {
                yield MenuItem::linkToCrud('Settings', 'fas fa-cog', Conf::class)
                    ->setAction('detail')
                    ->setEntityId(1)
                ;
            }
            yield MenuItem::linkToUrl('Changelog', 'fas fa-note-sticky', '/changelog/');
        }
        
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            yield MenuItem::section('Super Admin');
            yield MenuItem::linkToCrud('Region', 'fas fa-list', Region::class);
            yield MenuItem::linkToCrud('Node', 'fas fa-list', Node::class);
            yield MenuItem::linkToCrud('Language', 'fas fa-list', Language::class);
            yield MenuItem::linkToCrud('Conf', 'fas fa-cog', Conf::class);
        }
    }
}
