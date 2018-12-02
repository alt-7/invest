<?php

namespace application\models;

use application\core\Model;

class Dashboard extends Model
{
    public function historyCount()
    {
        $params = [
            'uid' => $_SESSION['account']['id'],
        ];

        return $this->db->column('SELECT count(id) FROM history WHERE uid = :uid', $params);
    }

    public function historyList($route)
    {
        $max    = 5;
        $params = [
            'max'   => $max,
            'start' => ((($route['page'] ?? 1) - 1) * $max),
            'uid'   => $_SESSION['account']['id'],
        ];

        return $this->db->row('SELECT * FROM history WHERE uid = :uid ORDER BY id DESC LIMIT :start, :max', $params);
    }

    public function referralsCount()
    {
        $params = [
            'uid' => $_SESSION['account']['id'],
        ];

        return $this->db->column('SELECT count(id) FROM accounts WHERE ref = :uid', $params);
    }

    public function referralsList($route)
    {
        $max    = 5;
        $params = [
            'max'   => $max,
            'start' => ((($route['page'] ?? 1) - 1) * $max),
            'uid'   => $_SESSION['account']['id'],
        ];

        return $this->db->row('SELECT login, email FROM accounts WHERE ref = :uid ORDER BY id DESC LIMIT :start, :max',
            $params);
    }

    public function tariffsCount()
    {
        $params = [
            'uid' => $_SESSION['account']['id'],
        ];

        return $this->db->column('SELECT count(id) FROM tariffs WHERE uid = :uid', $params);
    }

    public function tariffsList($route)
    {
        $max    = 5;
        $params = [
            'max'   => $max,
            'start' => ((($route['page'] ?? 1) - 1) * $max),
            'uid'   => $_SESSION['account']['id'],
        ];

        return $this->db->row('SELECT * FROM tariffs WHERE uid = :uid ORDER BY id DESC LIMIT :start, :max', $params);
    }

    public function creatRefWithdraw()
    {

        $amount                            = $_SESSION['account']['refBalance'];
        $_SESSION['account']['refBalance'] = 0;

        $params = [
            'id' => $_SESSION['account']['id'],
        ];
        $this->db->query('UPDATE accounts SET refBalance = 0 WHERE id = :id', $params);

        $params = [
            'uid'      => $_SESSION['account']['id'],
            'unixTime' => time(),
            'amount'   => $amount,
        ];
        $this->db->query('INSERT INTO ref_withdraw (uid, unixTime, amount) VALUES (:uid, :unixTime, :amount)', $params);

        $params = [
            'uid'         => $_SESSION['account']['id'],
            'unixTime'    => time(),
            'description' => 'Вывод реферального вознаграждения, сумма '.$amount.' $',
        ];
        $this->db->query('INSERT INTO history (uid, unixTime, description) VALUES (:uid, :unixTime, :description)',
            $params);
    }
}
