<?php namespace Forms;

include_once(MODX_BASE_PATH . 'assets/lib/MODxAPI/autoTable.abstract.php');

class Model extends \autoTable
{
    protected $table = 'form_results';
    protected $fields_table = 'forms_fields';
    protected $default_field = [
        'name'      => '',
        'email'     => '',
        'phone'     => '',
        'type'      => '',
        'ip'        => '',
        'createdon' => ''
    ];
    protected $form_fields = [];

    /**
     * @param  array  $fields
     * @return $this
     */
    public function setFormFields($fields = [])
    {
        if (!is_array($fields)) {
            return $this;
        }
        foreach ($fields as $field) {
            if (is_scalar($field)) {
                $this->form_fields[] = $field;
            }
        }
        $this->form_fields = array_unique($this->form_fields);

        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function edit($id)
    {
        parent::edit($id);
        if ($this->getID()) {
            $q = $this->modx->db->query("SELECT `field`, `value` FROM {$this->makeTable($this->fields_table)} WHERE `form` = {$this->getID()}");
            while ($row = $this->modx->db->getRow($q)) {
                $this->set($row['field'], $row['value']);
            }
        }

        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($key, $value)
    {
        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        parent::set($key, $value); // TODO: Change the autogenerated stub

        return $this;
    }


    /**
     * @param  bool  $fire_events
     * @param  bool  $clearCache
     * @return int|bool
     */
    public function save($fire_events = false, $clearCache = false)
    {
        if ($this->newDoc) {
            $time = $this->getTime(time());
            $this->set('createdon', date('Y-m-d H:i:s', $time));
            $this->set('ip', \APIhelpers::getUserIP());
        }

        if ($id = parent::save($fire_events, $clearCache)) {
            $insert = [];
            foreach ($this->form_fields as $field) {
                $value = $this->get($field);
                if (empty($value)) {
                    continue;
                }
                $field = $this->escape($field);
                $value = $this->escape($value);
                $insert[] = "($id, '{$field}', '{$value}')";
            }
            if ($insert) {
                $insert = implode(',', $insert);
                $this->query("INSERT IGNORE INTO {$this->makeTable($this->fields_table)} (`form`, `field`, `value`) VALUES {$insert}");
            }
        }

        return $id;
    }


    public function createTable()
    {
        $q = "CREATE TABLE IF NOT EXISTS {$this->makeTable($this->table)} (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `email` VARCHAR(80) NOT NULL,
            `phone` VARCHAR(20) NOT NULL,
            `type` VARCHAR(50) NOT NULL,
            `ip` VARCHAR(15) NOT NULL,
            `createdon` DATETIME NOT NULL,
            PRIMARY KEY  (`id`),
            KEY `type` (`type`),
            KEY `createdon` (`createdon`),
            KEY `email` (`email`),
            KEY `phone` (`phone`)
            ) Engine=InnoDB  DEFAULT CHARSET=utf8
            ";
        $this->modx->db->query($q);
        $q = "
            CREATE TABLE IF NOT EXISTS {$this->makeTable($this->fields_table)} (
            `id` INT(11) AUTO_INCREMENT,
            `form` INT(11) NOT NULL,
            `field` VARCHAR(255) NOT NULL DEFAULT '',
            `value` TEXT NOT NULL,
            PRIMARY KEY  (`id`),
            UNIQUE KEY (`form`, `field`),
            CONSTRAINT `forms_fields_fk` 
            FOREIGN KEY (`form`) 
            REFERENCES {$this->makeTable($this->table)} (`id`) 
            ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        $this->modx->db->query($q);
    }
}
