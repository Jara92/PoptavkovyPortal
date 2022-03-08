<?php

namespace App\Business\Service;

use App\Entity\Notification;
use App\Repository\Interfaces\IRepository;
use App\Repository\Interfaces\User\INotificationRepository;

/**
 * Service class which handles everything about CompanyContact.
 * @extends  AService<Notification, int>
 */
class NotificationService extends AService
{
    /** @var INotificationRepository */
    protected IRepository $repository;

    public function __construct(INotificationRepository $notificationRepository)
    {
        parent::__construct($notificationRepository);
    }
}