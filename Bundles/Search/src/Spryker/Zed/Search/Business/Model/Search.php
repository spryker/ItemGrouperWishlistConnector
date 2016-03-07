<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model;

use Elastica\Exception\ResponseException;
use Spryker\Client\Search\SearchClient;

// TODO: #1041 - This class needs to be reviewed and refactored
class Search
{

    /**
     * @var \Spryker\Client\Search\SearchClient
     */
    private $searchClient;

    /**
     * @param \Spryker\Client\Search\SearchClient $searchClient
     */
    public function __construct(SearchClient $searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        try {
            return $this->searchClient->getIndexClient()->count();
        } catch (ResponseException $e) {
            return 0;
        }
    }

    /**
     * @return array
     */
    public function getMetaData()
    {
        $metaData = [];

        try {
            $mapping = $this->searchClient->getIndexClient()->getMapping();

            if (isset($mapping['page']) && isset($mapping['page']['_meta'])) {
                $metaData = $mapping['page']['_meta'];
            }
        } catch (ResponseException $e) {
            // legal catch, if no mapping found (fresh installation etc) we still want to show empty meta data
        }

        return $metaData;
    }

    /**
     * @return \Elastica\Response
     */
    public function delete()
    {
        return $this->searchClient->getIndexClient()->delete();
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return \Elastica\Document
     */
    public function getDocument($key, $type)
    {
        return $this->searchClient->getIndexClient()->getType($type)->getDocument($key);
    }

}
