<?php
/**
 * Photo service.
 */

namespace App\Service;

use App\Entity\Photo;
use App\Entity\User;
use App\Repository\PhotoRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UserService.
 */
class PhotoService
{
    /**
     * Photo repository.
     *
     * @var PhotoRepository
     */
    private $photoRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * UserService constructor.
     *
     * @param PhotoRepository    $photoRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(PhotoRepository $photoRepository, PaginatorInterface $paginator)
    {
        $this->photoRepository = $photoRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->photoRepository->queryAll(),
            $page,
            PhotoRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save.
     *
     * @param Photo $photo
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Photo $photo): void
    {
        $this->photoRepository->save($photo);
    }

    /**
     * Delete.
     *
     * @param Photo $photo
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Photo $photo): void
    {
        $this->photoRepository->delete($photo);
    }
}
