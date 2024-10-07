<?php

class mysqli_class extends mysqli
{
    public function __construct()
    {
        require "../private/dbconf_truth.php";
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        parent::__construct(DBHost, DBUser, DBPass, DBName, DBPort);

        if(mysqli_connect_error()){
            trigger_error(mysqli_connect_error(), E_USER_WARNING);
            echo mysqli_connect_error();
            exit;
        }
    }

    public function login($email, $pass){
        $this->session_update();
        $query = "SELECT * FROM users WHERE email = ?";
        if ($stmt = parent::prepare($query)){
            $stmt->bind_param("s", $email);
            if(!$stmt->execute()){
                trigger_error($this->error, E_USER_WARNING);
            }
            $meta = $stmt->result_metadata();
            while($field = $meta->fetch_field()){
                $parameters[] = &$row[$field->name];
            }
            call_user_func_array(array($stmt, 'bind_result'), $parameters);

            $stmt->fetch();
            $x = array();
            foreach($row as $key => $val){
                $x[$key] = $val;
            }
            $stmt->close();

            if($x['email'] == $email && password_verify($pass, $x['password'])){
                try{
                    $this -> logins_insert($x['id']);
                    return array(1, $x);
                }catch (mysqli_sql_exception $e){
                    $this->login_remove($x['id']);
                    $this->login_insert($x['id']);
                    return array(1, $x);
                }
            } else {
                return array(0, $x);
            }
        }
        else {
            trigger_error($this->error, E_USER_WARNING);
        }
    }

    public function login_insert($user_id){
        $this->session_update();
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        $query = "
            INSERT INTO logins
                (user_id, login_ip, login_browser)
            VALUES (?, ?, ?)";
        if ($stmt = parent::prepare($query)) {
            $stmt->bind_param("iss", $user_id, $ip, $agent);
            if (!$stmt->execute()) {
                trigger_error($this->error, E_USER_WARNING);
            }
            $last_id = $this->insert_id;

            $stmt->close();
        }//END PREPARE
        else {
            trigger_error($this->error, E_USER_WARNING);
        }
        return $last_id;
    }

    public function login_remove($id)
    {
        $this->session_update();
        $query = "DELETE FROM logins WHERE user_id = ?";

        if ($stmt = parent::prepare($query)) {
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                trigger_error($this->error, E_USER_WARNING);
            }

            $stmt->close();
        }//END PREPARE
        else {
            trigger_error($this->error, E_USER_WARNING);
        }
    }

    //
    public function session_update(){
        // destroy session if it's last activity was over 30 minutes ago
        if((isset($_SESSION[PREFIX . '_last_activity'])) && (time() - $_SESSION[PREFIX . '_last_activity'] > 1800)){
            session_unset();
            session_destroy();
            return 0;
        }
        else{
            $_SESSION[PREFIX . '_last_activity'] = time();
            return 1;
        }
    }

    // Google Fact Check API connection point
    public function gfac_api_conn(){

    }

    // Google Search API connection point
    public function gsearch_api_conn(){

    }

    // Add new user to users DB
    public function add_user($email, $pass){

    }

    // Remove user from users DB
    public function remove_user($email){

    }

    // Grab results from
    public function fill_content($arr){

    }
}