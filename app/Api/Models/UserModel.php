<?php

namespace App\Api\Models;

use App\Api\Forms\UserForm;
use Mix\Database\Database;

/**
 * Class UserModel
 * @package App\Api\Models
 * @author liu,jian <coder.keda@gmail.com>
 */
class UserModel
{

    /**
     * @var Database
     */
    public $db;

    /**
     * UserModel constructor.
     */
    public function __construct()
    {
        $this->db = context()->get('database');
    }

    /**
     * 新增用户
     * @param UserForm $model
     * @return bool|string
     */
    public function add(UserForm $form)
    {
        $conn     = $this->db->insert('user', [
            'name'  => $form->name,
            'age'   => $form->age,
            'email' => $form->email,
        ]);
        $status   = $conn->execute();
        $insertId = $status ? $conn->getLastInsertId() : false;
        return $insertId;
    }

}
