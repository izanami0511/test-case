<?php

namespace App\Controller\Admin;

use App\Entity\Appointment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class AppointmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Appointment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('user', 'Пациент')
            ->onlyOnForms();
        yield AssociationField::new('doctor', 'Врач')
            ->onlyOnForms();
        yield TextareaField::new('comment', 'Комментарий');
        yield DateTimeField::new('visitDate', 'Время записи');
    }
}
