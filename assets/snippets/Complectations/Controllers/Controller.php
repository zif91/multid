<?php

namespace EvolutionCMS\Complectations\Controllers;

use EvolutionCMS\Complectations\Services\Service;

include_once __DIR__."/../Services/Service.php";

class Controller
{
    /**
     * @var \DocumentParser
     */
    private \DocumentParser $evo;

    /**
     * @var Service
     */
    private Service $service;

    /**
     * @var string
     */
    private string $configurations;

    /**
     * @param  \DocumentParser  $modx
     */
    public function __construct(\DocumentParser $modx)
    {
        $this->evo = $modx;
        $this->service = new Service($this->evo);
    }

    /**
     * @param  int  $id
     * @return Controller
     */
    public function car(int $id): Controller
    {
        $this->configurations = $this->service->getByCarId($id);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->service->toArray($this->configurations);
    }
}
