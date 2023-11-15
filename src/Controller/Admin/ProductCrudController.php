<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('name'),
            TextEditorField::new('description')->onlyOnForms(),
            NumberField::new('price'),
            AssociationField::new('category'),
            BooleanField::new('isAvailable'),
            ImageField::new('imagePath')
                ->setBasePath('/images/products/') // Seteaza calea pentru afisarea imaginii
                ->onlyOnIndex(), // Afiseaza imaginea pe pagina index
            Field::new('imageFile')
                ->setFormType(VichImageType::class) // foloseste VichImageField
                ->setLabel('Product Image')
                ->setRequired(false) // Image is optional
                ->onlyOnForms(),
        ];
    }
}
