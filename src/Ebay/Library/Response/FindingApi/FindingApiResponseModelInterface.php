<?php

namespace App\Ebay\Library\Response\FindingApi;


use App\Ebay\Library\Response\ResponseModelInterface;

interface FindingApiResponseModelInterface extends ResponseModelInterface
{
    /**
     * @param null $default
     * @return mixed
     */
    public function getAspectHistogramContainer($default = null);
    /**
     * @param null $default
     * @return mixed
     */
    public function getSearchResults($default = null);
    /**
     * @param null $default
     * @return mixed
     */
    public function getConditionHistogramContainer($default = null);
    /**
     * @param null $default
     * @return mixed
     */
    public function getPaginationOutput($default = null);
    /**
     * @param null $default
     * @return mixed
     */
    public function getCategoryHistogramContainer($default = null);
    /**
     * @param null $default
     * @return mixed
     */
    public function getErrors($default = null);
}