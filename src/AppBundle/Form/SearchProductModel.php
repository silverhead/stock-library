<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 2019-01-15
 * Time: 22:42
 */

namespace AppBundle\Form;


class SearchProductModel
{
    /**
     * @var string
     */
    private $search;

    /**
     * @return string
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @param string $search
     * @return SearchProductModel
     */
    public function setSearch(string $search = null): SearchProductModel
    {
        $this->search = $search;

        return $this;
    }


}