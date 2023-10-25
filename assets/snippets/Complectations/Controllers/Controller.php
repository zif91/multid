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
     * @param  array  $config
     */
    public function __construct(\DocumentParser $modx, array $config = [])
    {
        $this->evo = $modx;
        $this->config = $config;
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

    /**
     * @return float
     */
    public function price(): float
    {
        return $this->service->getMinimalPrice();
    }

    /**
     * @return float
     */
    public function priceOld(): float
    {
        return $this->service->getMinimalOldPrice();
    }

    public function all()
    {
        if (!empty($this->config['toObject']) && (int) $this->config['toObject'] === 1) {
            return $this;
        }

        return $this->service->toArray($this->configurations);
    }
}
