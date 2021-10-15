<?php
class plugins_mondialrelay_db
{
    /**
     * @param $config
     * @param bool $params
     * @return mixed|null
     * @throws Exception
     */
    public function fetchData($config, $params = false)
    {
        if (!is_array($config)) return '$config must be an array';

        $sql = '';

        if ($config['context'] === 'all') {
            switch ($config['type']) {
                case 'data':
                    $sql = 'SELECT mo.* FROM mc_mondial_relay AS mo';
                    break;
            }

            return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
        }
        elseif ($config['context'] === 'one') {
            switch ($config['type']) {
                case 'root':
                    $sql = 'SELECT * FROM mc_mondial_relay ORDER BY id_mrelay DESC LIMIT 0,1';
                    break;
                /*case 'history':
                    $sql = 'SELECT * FROM mc_mondial_relay_history WHERE order_h = :order_h';
                    break;
                case 'lastHistory':
                    $sql = 'SELECT * FROM mc_mondial_relay_history ORDER BY id_mollie_h DESC LIMIT 0,1';
                    break;*/
            }

            return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
        }
    }
    /**
     * @param $config
     * @param array $params
     * @throws Exception
     */
    public function insert($config, $params = array())
    {
        if (!is_array($config)) return '$config must be an array';

        $sql = '';

        switch ($config['type']) {
            case 'newConfig':

                $sql = 'INSERT INTO mc_mondial_relay (merchant, privatekey)
                VALUE(:merchant, :privatekey)';

                break;
            /*case 'history':

                $sql = 'INSERT INTO mc_mondial_relay_history (order_h,status_h)
                VALUE(:order_h,:status_h)';

                break;*/
        }

        if($sql === '') return 'Unknown request asked';

        try {
            component_routing_db::layer()->insert($sql,$params);
            return true;
        }
        catch (Exception $e) {
            return 'Exception reçue : '.$e->getMessage();
        }

    }

    /**
     * @param $config
     * @param array $params
     * @throws Exception
     */
    public function update($config, $params = array())
    {
        if (!is_array($config)) return '$config must be an array';

        $sql = '';

        switch ($config['type']) {
            case 'config':
                $sql = 'UPDATE mc_mondial_relay
                    SET merchant = :merchant,
                        privatekey = :privatekey
                    WHERE id_mrelay=:id';
                break;
        }

        if($sql === '') return 'Unknown request asked';

        try {
            component_routing_db::layer()->update($sql,$params);
            return true;
        }
        catch (Exception $e) {
            return 'Exception reçue : '.$e->getMessage();
        }
    }
}
?>