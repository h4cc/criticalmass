<?php

namespace Caldera\Bundle\CriticalmassCoreBundle\Timeline\Item;

abstract class AbstractItem implements ItemInterface
{
    /**
     * @var mixed $uniqId
     */
    protected $uniqId;

    /**
     * @var \DateTime $dateTime
     */
    protected $dateTime;

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setDateTime(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function __construct()
    {
        $this->uniqId = uniqid();
    }

    public function getUniqId()
    {
        return $this->uniqId;
    }
}