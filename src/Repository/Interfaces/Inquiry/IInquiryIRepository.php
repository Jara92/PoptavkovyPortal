<?php

namespace App\Repository\Interfaces\Inquiry;

use App\Entity\Inquiry\Inquiry;
use App\Repository\Interfaces\IRepository;
use App\Tools\Filter\InquiryFilter;
use App\Tools\Pagination\PaginationData;
use DateTime;

/**
 * @implements IRepository<Inquiry, int>
 */
interface IInquiryIRepository extends IRepository
{
    /**
     * Returns results given by filter object.
     * @param InquiryFilter $filter
     * @param PaginationData $paginationData
     * @param array $ordering
     * @return mixed
     */
    public function findByFilterPaginated(InquiryFilter $filter, PaginationData $paginationData, array $ordering = []);

    /**
     * Returns the given number of inquiries similar to the given inquiry.
     * @param Inquiry $inquiry
     * @param int $maxResults
     * @param array $ordering
     * @return Inquiry[]
     */
    public function findSimilar(Inquiry $inquiry, int $maxResults = 10, array $ordering = []): array;

    /**
     * Returns active inquiries with i.removeAt <= $date
     * @param DateTime $date
     * @return Inquiry[]
     */
    public function findActiveAndRemoveAtLessThan(DateTime $date): array;

    /**
     * Returns active inquiries with i.removeNoticeAt <= $date
     * @param DateTime $date
     * @return Inquiry[]
     */
    public function findActiveAndRemoveNoticeAtLessThan(DateTime $date): array;
}