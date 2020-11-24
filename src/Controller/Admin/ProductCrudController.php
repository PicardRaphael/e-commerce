<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $illustrationField = ImageField::new('illustrationFile')
            ->setFormType(VichImageType::class)
            ->onlyOnForms()
            ->setLabel('Image');

        $illustration = ImageField::new('illustration')
            ->setUploadDir("public/uploads/images/products")
            ->hideOnForm()
            ->setLabel('Image');
        //->setFormTypeOptions(['mapped' => false,'required' => false])
        $fields =  [
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'),
            TextField::new('subtitle'),
            TextareaField::new('description'),
            MoneyField::new('price')->setCurrency('EUR'),
            AssociationField::new('category')
        ];

        if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_DETAIL) {
            $fields[] = $illustration;
        } else {
            $fields[] = $illustrationField;
        }

        return $fields;
    }
}
