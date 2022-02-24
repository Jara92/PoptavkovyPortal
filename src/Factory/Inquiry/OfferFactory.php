<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\Offer;
use App\Entity\User;

class OfferFactory
{
    public function createOffer(User $author, Inquiry $inquiry): Offer
    {
        return (new Offer())->setAuthor($author)->setInquiry($inquiry);
    }
}