<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $appKernel;

    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setIllustration'],
            BeforeEntityUpdatedEvent::class => ['updateIllustration']
        ];
    }

    public function uploadIllustration($event)
    {
        $entity = $event->getEntityInstance();
        $tmp_name = $_FILES['Product']['name']['illustration']['file'];
        $filename = uniqid();
        $extension = pathinfo($_FILES['Product']['name']['illustration']['file'], PATHINFO_EXTENSION);

        $project_dir = $this->appKernel->getProjectDir();
        rename(
            $project_dir . '/public/uploads/images/products/' . $tmp_name,
            $project_dir . '/public/uploads/images/products/' . $filename . '.' . $extension
        );

        $entity->setIllustration($filename . '.' . $extension);
    }

    public function updateIllustration(BeforeEntityUpdatedEvent $event)
    {
        if (!($event->getEntityInstance() instanceof Product)) {
            return;
        }

        if ($_FILES['Product']['name']['illustration']['file'] != '') {
            $this->uploadIllustration($event);
        }
    }

    public function setIllustration(BeforeEntityPersistedEvent $event)
    {
        if (!($event->getEntityInstance() instanceof Product)) {
            return;
        }

        $this->uploadIllustration($event);
    }
}
