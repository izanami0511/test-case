<?php

namespace App\Controller\Admin;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Specialty;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        $route = $adminUrlGenerator->setController(DoctorCrudController::class)->generateUrl();

        return $this->redirect($route);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Project');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Записи', 'fas fa-list', Appointment::class);
        yield MenuItem::linkToCrud('Пользователи', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Специальности', 'fas fa-list', Specialty::class);
    }
}
