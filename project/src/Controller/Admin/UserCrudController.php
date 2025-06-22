<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('Пользователь')
            ->setEntityLabelInPlural('Пользователи');
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->onlyOnIndex();
        yield EmailField::new('email', 'Почта');
        yield TextField::new('phone', 'Телефон');
        yield TextField::new('fullname', 'ФИО');
        yield TextField::new('birthdate', 'Дата рождения');
        yield ChoiceField::new('roles', 'Роли')
            ->allowMultipleChoices()
            ->renderAsBadges()
            ->setChoices([
                'Админ' => 'ROLE_ADMIN',
                'Доктор' => 'ROLE_DOCTOR',
                'Пользователь' => 'ROLE_USER',
            ])
            ->setHelp('Определяет уровень допуска пользователя');
        yield TextField::new('plainPassword', 'Пароль')
            ->setFormType(PasswordType::class)
            ->setFormTypeOptions([
                'hash_property_path' => 'password',
                'mapped' => false,
            ])
            ->setRequired(true)
            ->onlyOnForms();
    }

}
