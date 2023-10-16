<?php

namespace EvolutionCMS\Complectations\Services;

class Service
{
    /**
     * @var \modResource
     */
    private \modResource $model;

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
            $configuration_item = ['title_group' => $configuration['value']];
            if (!empty($configuration['items'])) {
                foreach ($configuration['items'] as $item_key => $item) {
                    if (!empty($item['items'])) {
                        foreach ($item['items'] as $item_item_key => $item_item) {
                            $configuration_item['items'][$item_key][$item_item_key] = $item_item['value'];
                        }
                    }
                }
            }
            $items[] = $configuration_item;
        }

        return $items;
    }
}
