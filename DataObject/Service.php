<?php


namespace OxidProfessionalServices\Usercentrics\DataObject;

class Service
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $id;

    /**
     * Service constructor.
     * @param string $name
     * @param string $id
     */
    public function __construct(string $name, string $id)
    {
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

}
