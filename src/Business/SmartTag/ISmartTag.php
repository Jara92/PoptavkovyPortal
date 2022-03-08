<?php

namespace App\Business\SmartTag;

use App\Entity\Inquiry\Inquiry;

/**
 * Interface for dynamically added inquiry tags.
 * Derived classes must be registered in SmartTagService.
 */
interface ISmartTag
{
    /**
     * Returns smart tag's title to be displayed.
     * @return string
     */
    public function getTitle(): string;

    /**
     * Returns smart tag's unique alias.
     * @return string
     */
    public function getAlias(): string;

    /**
     * Is given inquiry corresponding to this SmartTag?
     * @param Inquiry $inquiry
     * @return bool
     */
    public function correspondsTo(Inquiry $inquiry): bool;
}