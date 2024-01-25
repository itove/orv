<?php

namespace App\Controller\Admin;

use App\Entity\Conf;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use App\Admin\Field\VichImageField;

class ConfCrudController extends AbstractCrudController
{
    private $action;
    private $adminUrlGenerator;
    
    public function __construct(RequestStack $requestStack, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->query = $requestStack->getCurrentRequest()->query;
        $this->action = $this->query->get('action');
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    
    public static function getEntityFqcn(): string
    {
        return Conf::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $editFn = fn (Action $action) => $action->linkToUrl(fn (Conf $entity) =>
            $this->adminUrlGenerator
                 ->setAction('edit')
                 ->set('entityId', $entity->getId())
                 ->includeReferrer()
                 ->generateUrl()
        );
        
        return $actions
            ->update(Crud::PAGE_DETAIL, Action::EDIT, $editFn)
            // ->disable('new')
            ->disable(Action::SAVE_AND_ADD_ANOTHER)
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        switch ($this->action) {
            case 'contact':
                $fields = [
                    TextField::new('address'),
                    TextField::new('phone'),
                    TextField::new('email'),
                ];
                break;
            default:
                $fields = [
                    TextField::new('Sitename'),
                    TextField::new('company'),
                    ArrayField::new('keywords'),
                    TextareaField::new('description'),
                    TextField::new('address'),
                    TextField::new('phone'),
                    TextField::new('phone2'),
                    TextField::new('email'),
                    AssociationField::new('language'),
                    ImageField::new('logo')
                        ->onlyOnIndex()
                        ->setBasePath('images/')
                        ->setUploadDir('public/images/')
                    ,
                    VichImageField::new('logoFile', 'Logo')->hideOnIndex(),
                ];
        }
        
        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('detail', 'Conf')
        ;
    }
}
