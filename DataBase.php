<?php
$db_host = 'localhost';
$db_user = 'hanni';
$db_pass = 'hanni';
$db_name = 'Users';

$myconn = new mysqli ($db_host, $db_user, $db_pass, $db_name);

class User
{
    public $id;
    public $name;
    public $surname;
    public $birthDate;
    public $gender;
    public $birthCity;

    public function __construct($id = null, $name = null, $surname = null, $birthDate = null, $gender = null, $birthCity = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->birthDate = $birthDate;
        $this->gender = $gender;
        $this->birthCity = $birthCity;

        if ($this->id)
        {
            global $myconn;
            $sql = "SELECT * FROM Users WHERE id = '{$this->id}'";
            $guery = mysqli_query($myconn, $sql);
            while ($row = mysqli_fetch_assoc($guery)) {
                $id = $row['id'];
                $name = $row['name'];
                $surname = $row['surname'];
                $birthDate = $row['birthDate'];
                $gender = $row['gender'];
                $birthCity = $row['birthCity'];
                echo $id . " " . $name . " " . $surname . " " . $birthDate . " " . $gender
                    . " " . $birthCity;
            }
        } else {
            if (preg_match('/^[a-zA-Zа-яёА-ЯЁ]+$/u', $name) &&
                preg_match('/^[a-zA-Zа-яёА-ЯЁ]+$/u', $surname)) {
                self::save();
            } else {
                echo "Некорректно заполнено поле";
            }
        }
    }

    public function save()
    {
        global $myconn;
        $sql = "INSERT INTO Users (`name`, `surname`, `birthDate`, `gender`, `birthCity`)
        VALUES ('{$this->name}', '{$this->surname}', '{$this->birthDate}', '{$this->gender}', '{$this->birthCity}')";
        $guery = mysqli_query($myconn, $sql);
        return $guery;
    }

    public function delete()
    {
        global $myconn;

        $sql = "DELETE FROM Users WHERE id = '{$this->id}'";
        $query = mysqli_query($myconn, $sql);
        return $query;
    }

    public static function birthDateConvert($user)
    {
        $newUser = clone $user;
        $birthDate = new DateTime($newUser->birthDate);
        $interval = $birthDate->diff(new DateTime);
        $result = $interval->y;
        $newUser->birthDate = $result;
        return $newUser;
    }

    public static function genderConvert($user)
    {
        $newUser = clone $user;
        if ($newUser->gender == 0) {
            $a = "Женщина";
        } elseif ($newUser->gender == 1) {
            $a =  "Мужчина";
        } else {
            throw new Exception('gender error');
        }
        $newUser->gender = $a;
        return $newUser;
    }
}
$user = new User('', 'Ольга', 'Величко', '1990.08.21', 0, 'Слоним');
User::genderConvert($user);
User::birthDateConvert($user);

