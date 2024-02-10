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

    /**
     * @return string
     */
    public function engine(): string
    {
        return $this->service->getFirstEngine();
    }

    /**
     * @return string
     */
    public function expense(): string
    {
        return $this->service->getFirstExpense();
    }

    /**
     * @return string
     */
    public function power(): string
    {
        return $this->service->getFirstPower();
    }

    /**
     * @return string
     */
    public function getConfigurations(): string
    {
        return $this->configurations;
    }

    public function all()
    {
        $configurations = $this->service->toArray($this->configurations);

        if (!empty($this->config['toObject']) && (int) $this->config['toObject'] === 1) {
            return $this;
        }

        return $configurations;
    }
}
