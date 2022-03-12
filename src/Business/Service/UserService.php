<?php /** @noinspection PhpUnusedAliasInspection */

namespace App\Business\Service;

use App\Entity\User;
use App\Enum\Entity\UserType;
use App\Factory\UserFactory;
use App\Repository\Interfaces\IRepository;
use App\Repository\Interfaces\User\IUserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * Abstract service which implements basic service features.
 * @laslesvpn_template E
 * @laslesvpn_template K
 * @extends AService<User, int>
 */
class UserService extends AService
{
    protected UserFactory $userFactory;

    /** @var IUserRepository */
    protected IRepository $repository;

    public function __construct(IUserRepository $userRepository, UserFactory $userFactory)
    {
        parent::__construct($userRepository);
        $this->userFactory = $userFactory;
    }

    /**
     * Returns user by email.
     * @param string $email
     * @return User|null
     */
    public function readByEmail(string $email): ?User
    {
        return $this->repository->findOneBy(["email" => $email]);
    }

    /**
     * Returns formated user's name based on user's type.
     * @param User $user
     * @return string
     * @throws \LogicException When user's type is unknown.
     */
    public function getFormatedUserName(User $user): string
    {
        switch ($user->getType()) {
            case UserType::PERSON:
                return $user->getPerson()->getName() . " " . $user->getPerson()->getSurname();
            case UserType::COMPANY:
                return $user->getCompany()->getName();
            default:
                throw new \LogicException("Unknown user type");
        }
    }
}