<?php
/**
 * Gallery service.
 */

namespace App\Service;

use App\Entity\Gallery;
use App\Repository\GalleryRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class GalleryService.
 */
class GalleryService
{
    /**
     * @var GalleryRepository
     */
    private $galleryRepository;

    /**
     * @var PaginatorInterface|PaginatorInterface
     */
    private $paginator;

    /**
     * GalleryService constructor.
     *
     * @param GalleryRepository  $galleryRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(GalleryRepository $galleryRepository, PaginatorInterface $paginator)
    {
        $this->galleryRepository = $galleryRepository;
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
            $this->galleryRepository->queryAll(),
            $page,
            GalleryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save.
     *
     * @param Gallery $gallery
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Gallery $gallery): void
    {
        $this->galleryRepository->save($gallery);
    }

    /**
     * Delete.
     *
     * @param Gallery $gallery
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Gallery $gallery): void
    {
        $this->galleryRepository->delete($gallery);
    }
}
