<?php

/**
 *
 * @Entity @Table(name="media")
 * @author overkill
 * 
 */
class media {
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     */
    protected $id;
    /**
     * @Column(type="decimal",precision=10)
     * @var decimal
     */
    protected $latitude;
    /**
     * @Column(type="decimal", precision=10)
     * @var decimal
     */
    protected $longitude;

    public function getId()
    {
        return $this->id;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }
    
    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }
}
