<?php

namespace App\Tools\Filter;

use App\Entity\Inquiry\InquiryCategory;
use App\Entity\Inquiry\Region;
use App\Entity\Inquiry\InquiryValue;
use App\Entity\Inquiry\InquiryType;

class InquiryFilter
{
    protected ?string $text = "";

    protected array $categories = [];

    protected array $regions = [];

    protected array $values = [];

    protected array $types = [];

    // TODO: figure out datatype and better name.
    protected mixed $old;

    protected ?string $ordering;

    /**
     * @return ?string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param null|string $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return InquiryCategory[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param InquiryCategory[] $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @return Region[]
     */
    public function getRegions(): array
    {
        return $this->regions;
    }

    /**
     * @param Region[] $regions
     */
    public function setRegions(array $regions): void
    {
        $this->regions = $regions;
    }

    /**
     * @return InquiryValue[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param InquiryValue[] $values
     */
    public function setValues(array $values): void
    {
        $this->values = $values;
    }

    /**
     * @return InquiryType[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @param InquiryType[] $types
     */
    public function setTypes(array $types): void
    {
        $this->types = $types;
    }

    /**
     * @return mixed
     */
    public function getOld()
    {
        return $this->old;
    }

    /**
     * @param mixed $old
     */
    public function setOld($old): void
    {
        $this->old = $old;
    }

    /**
     * @return null|string
     */
    public function getOrdering(): ?string
    {
        return $this->ordering;
    }

    /**
     * @param null|string $ordering
     */
    public function setOrdering(?string $ordering): void
    {
        $this->ordering = $ordering;
    }


}