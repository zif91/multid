<?php

namespace EvolutionCMS\Complectations\Services;

class Service
{
    /**
     * @var \modResource
     */
    private \modResource $model;

    /**
     * @var float
     */
    private float $price_min = 0;

    /**
     * @var float
     */
    private float $price_old_min = 0;

    /**
     * @var string
     */
    private string $engine = "";

    /**
     * @var string
     */
    private string $expense = "";

    /**
     * @param  \DocumentParser  $modx
     */
    public function __construct(\DocumentParser $modx)
    {
        $this->model = new \modResource($modx);
    }

    /**
     * @param  int  $id
     * @return string
     */
    public function getByCarId(int $id): string
    {
        $this->model->edit($id);
        $value = $this->model->get("car_characteristics");
        $this->model->close();

        return (string) $value;
    }

    /**
     * @param  string  $configuration
     * @return array
     */
    public function toArray(string $configuration): array
    {
        $items = [];
        $configurations = json_decode($configuration, true);

        if (empty($configurations)) {
            return [];
        }

        foreach ($configurations as $configuration) {
            $items[] = $this->formatConfiguration($configuration);
        }

        return $items;
    }

    /**
     * @return float
     */
    public function getMinimalPrice(): float
    {
        return $this->price_min;
    }

    /**
     * @return float
     */
    public function getMinimalOldPrice(): float
    {
        return $this->price_old_min;
    }

    /**
     * @return string
     */
    public function getFirstEngine(): string
    {
        return $this->engine;
    }

    /**
     * @return string
     */
    public function getFirstExpense(): string
    {
        return $this->expense;
    }

    /**
     * @param  array  $configuration
     * @return array
     */
    private function formatConfiguration(array $configuration): array
    {
        $configuration_item = ['title_group' => $configuration['value']];
        if (!empty($configuration['items'])) {
            foreach ($configuration['items'] as $item_key => $item) {
                if (!empty($item['items'])) {
                    foreach ($item['items'] as $item_item_key => $item_item) {
                        if ($item_item_key === "engine_type" && !empty($item_item['value']) && empty($this->engine)) {
                            $this->engine = $item_item['value'];
                        }

                        if ($item_item_key === "expense" && !empty($item_item['value']) && empty($this->expense)) {
                            $this->expense = $item_item['value'];
                        }

                        if ($item_item_key === "price" && (empty($this->price_min) || (!empty($item_item['value']) && $this->formatPrice($item_item['value']) < $this->price_min))) {
                            $this->price_min = $this->formatPrice($item_item['value']);
                            if (!empty($item['items']['price_old']['value'])) {
                                $this->price_old_min = $this->formatPrice($item['items']['price_old']['value']);
                            }
                        }
                        $configuration_item['items'][$item_key][$item_item_key] = $item_item['value'];
                    }
                }
            }
        }

        return $configuration_item;
    }

    /**
     * @param  string  $price
     * @return float
     */
    private function formatPrice(string $price): float
    {
        return (float) str_replace(" ", "", $price);
    }
}
