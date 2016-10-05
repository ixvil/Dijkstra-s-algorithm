<?php

/**
 * Implementation of Dijkstra's algorithm
 * Improved Dijkstra's algorithm, now it also stores way and stops in finish
 * Developed by Shipin Alexey for Unitecsys
 */
class ShortestWayFinder
{
    private $citiesIndex = array();
    private $visitedCities = array();
    private $indexedRoadsList = array();
    private $toPoint;


    /**
     * Main function. Finds cheapest way
     *
     * @param $fromPoint    Start city
     * @param $toPoint      Destination city
     * @param $roadsList    List, describes available roads and costs
     * @return array        Array, contains cheapest way and its cost
     */
    function getCheapestWay($fromPoint, $toPoint, $roadsList)
    {
        $this->toPoint = $toPoint;
        $this->indexPoints($roadsList);

        $this->citiesIndex[$fromPoint] = 0;
        $this->visitedCities[$fromPoint] = array('cost' => 0, 'way' => array($fromPoint));

        while ($this->getNextPoint()) {
        };

        if (isset($this->visitedCities[$this->toPoint])) {
            return $this->visitedCities[$this->toPoint];
        } else {
            return false;
        }
    }


    /**
     * This function gets next point with minimal cost,
     * and check and update all nearest points.
     *
     * @return bool     Returns true, if it was not last time, false for last (if the cheapest way was founded)
     */
    function getNextPoint()
    {
        /** @var String $nextCity   Unvisited city with minimal cost */
        $nextCity = array_keys($this->citiesIndex, min($this->citiesIndex))[0];
        if (!isset($nextCity)) {
            /** There is no way between this cities */
            return false;
        }
        if ($nextCity == $this->toPoint) {
            /** We have reached goal, this city is finish */
            return false;
        }

        foreach ($this->indexedRoadsList[$nextCity] as $city => $cost) {
            /** If city is not unvisited, its cheapest way already founded,
             * because every execution of this method, it works with city, that have >= cost, then previous */
            if (!isset($this->citiesIndex[$city])) continue;

            /** @var integer $complexCost Full cost from start point to this point */
            $complexCost = $this->citiesIndex[$nextCity] + $cost;
            if ($complexCost < $this->citiesIndex[$city]) {
                $this->citiesIndex[$city] = $complexCost;
                $this->visitedCities[$city] = array(
                    'cost' => $complexCost,
                    'way' => array_merge(
                        $this->visitedCities[$nextCity]['way'],
                        array($city)
                    )
                );
            }
        }
        /** City is not unvisited more */
        unset($this->citiesIndex[$nextCity]);

        return true;
    }


    /**
     * This function is for preparing input data to work with  Dijkstra's algorithm
     *
     * @param $roadsList    List with all available roads and its costs
     */
    function indexPoints($roadsList)
    {
        foreach ($roadsList as $road) {
            $this->citiesIndex[$road[0]] = INF;
            $this->citiesIndex[$road[1]] = INF;

            /** Remapped road list, for faster search
             * Searching with indexes will be much faster, than searches with values */
            if (!isset($this->indexedRoadsList[$road[0]])) {
                $this->indexedRoadsList[$road[0]] = array();
            }
            if (!isset($this->indexedRoadsList[$road[1]])) {
                $this->indexedRoadsList[$road[1]] = array();
            }
            $this->indexedRoadsList[$road[0]][$road[1]] = $road[2];
            $this->indexedRoadsList[$road[1]][$road[0]] = $road[2];
        }
    }

}


/**
 * Example data
 */

$citiesList = array(
    array("First", "Second", 3),
    array("First", "Fourth", 7),
    array("Second", "Third", 9),
    array("Second", "Fourth", 2),
    array("Fourth", "Third", 1)

);

/**
 * Example execution
 */
$finder = new ShortestWayFinder();
$result = $finder->getCheapestWay("First", "Third", $citiesList);
print_r($result);