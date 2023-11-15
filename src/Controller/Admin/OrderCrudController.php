<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setFormTypeOption('disabled', true),
            TextField::new('firstName')->onlyOnForms(),
            TextField::new('lastName')->onlyOnForms(),
            EmailField::new('email'),
            TextareaField::new('address')->onlyOnForms(),
            TextField::new('country'),
            TextField::new('state'),
            TextField::new('zip')->onlyOnForms(),
            MoneyField::new('revenue')->setCurrency('RON')->setStoredAsCents(false)->setFormTypeOption('disabled', true),
            AssociationField::new('user')->onlyOnForms(),
            AssociationField::new('orderItems'),
            ArrayField::new('orderItems')->setFormTypeOption('by_reference', false)->onlyOnForms()->setFormTypeOption('disabled', true),
            BooleanField::new('isPaid')
        ];
    }
}
