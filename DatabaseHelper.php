<?php

class DatabaseHelper
{
    // Since the connection details are constant, define them as const
    // We can refer to constants like e.g. DatabaseHelper::username
    const username = 'xxxxxxxx'; // use a + your matriculation number
    const password = 'xxxxxxxx'; // use your oracle db password
    const con_string = 'example.url:prot'; //SQL DB URL

    // Since we need only one connection object, it can be stored in a member variable.
    // $conn is set in the constructor.
    protected $conn;

    // Create connection in the constructor
    public function __construct()
    {
        try {
            // Create connection with the command oci_connect(String(username), String(password), String(connection_string))
            $this->conn = oci_connect(
                DatabaseHelper::username,
                DatabaseHelper::password,
                DatabaseHelper::con_string
            );

            //check if the connection object is != null
            if (!$this->conn) {
                // die(String(message)): stop PHP script and output message:
                die("DB error: Connection can't be established!");
            }

        } catch (Exception $e) {
            die("DB error: {$e->getMessage()}");
        }
    }

    public function __destruct()
    {
        // clean up
        oci_close($this->conn);
    }

    //Takes password+data and veryfies the password with the encrypted one in the DB for depending username.
    public function checkLoginData($user, $passwort)
    {
        $sql = "SELECT * FROM Benutzer WHERE Name LIKE '$user'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_fetch($statement);
        $res = oci_result($statement, 'PASSWORT');
        oci_free_statement($statement);
        return password_verify($passwort, $res);
    }

    //Gets an array of all usernames in the DB.
    public function getBenutzerNamen()
    {
        $sql = "SELECT * FROM Benutzer";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        $res_array = array();
        while (oci_fetch_array($statement)) {
            $res = oci_result($statement, 'NAME');
            array_push($res_array, $res);
        }
        oci_free_statement($statement);
        return $res_array;
    }

    //Creates a new Benutzer in the DB by name and password.
    public function registerNewBenutzer($name, $passwort)
    {
        $sql = "INSERT INTO Benutzer(Name, Passwort) VALUES ('$name', '$passwort')";
        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    //Creates a new wall in the Database.
    public function registerNewWall($name)
    {
        $sql = "INSERT INTO Wall(Benutzer) VALUES ('$name')";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
    }

    //Gets the corresponding Benutzer_ID for a certain name.
    public function getBenutzerIDByName($name)
    {
        $sql = "SELECT * FROM Benutzer WHERE Name LIKE '$name'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_fetch($statement);
        $res = oci_result($statement, 'BENUTZER_ID');
        oci_free_statement($statement);
        return $res;
    }

    //Gets all Posts that were made by a corresponding Benutzer_ID.
    public function getPostsByBenutzerID($id)
    {
        $sql = "SELECT * FROM hat WHERE Benutzer_ID LIKE '$id'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        $res_array = array();
        while (oci_fetch_array($statement)) {
            $res = oci_result($statement, 'POST_ID');
            array_push($res_array, $res);
        }
        oci_free_statement($statement);
        return $res_array;
    }

    //Gets the Text from Textposts that have the corresponding Post_ID.
    public function getPostTextByID($id)
    {
        $sql = "SELECT * FROM TextPost WHERE Post_ID LIKE '$id'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_fetch($statement);
        $res = oci_result($statement, 'TEXT_');
        oci_free_statement($statement);
        return $res;
    }

    //Returns the Username of the Posting User.
    public function getUsernameByID($id)
    {
        $sql = "SELECT * FROM TextPost WHERE Post_ID LIKE '$id'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_fetch($statement);
        $res = oci_result($statement, 'BENUTZER');
        oci_free_statement($statement);
        return $res;
    }

    //Gets all Posts to a corrisponding Wall_ID.
    public function getPostsByWallID($id)
    {
        $sql = "SELECT * FROM hat WHERE Wall_ID LIKE '$id'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        $res_array = array();
        while (oci_fetch_array($statement)) {
            $res = oci_result($statement, 'POST_ID');
            array_push($res_array, $res);
        }
        oci_free_statement($statement);
        return $res_array;
    }

    //Gets the Date of a TextPost by a certain Post_ID in a defined format.
    public function getPostDateByID($id)
    {
        $sql = "alter session set nls_date_format='HH24:MI:SS DD MON RRRR'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        $sql = "SELECT * FROM TextPost WHERE Post_ID LIKE '$id'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_fetch($statement);
        $res = oci_result($statement, 'DATUMUHRZEIT');
        oci_free_statement($statement);
        return $res;
    }

    //Returns the number of reactions that a certain post has by Post_ID.
    public function getNumberReaktionsByPostID($id)
    {
        $sql = "SELECT * FROM Reaktion WHERE Post_ID LIKE '$id'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        $res_array = array();
        while (oci_fetch_array($statement)) {
            $res = oci_result($statement, 'POST_ID');
            array_push($res_array, $res);
        }
        oci_free_statement($statement);
        if (empty($res_array)) {
            return 0;
        }
        return count($res_array);
    }

    //Deletes a TextPost by Post_ID.
    public function deletePostByID($id)
    {
        $sql = "DELETE FROM TextPost WHERE Post_ID = '$id'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_free_statement($statement);
    }

    //Creates a Reaktion with a certain Benutzer_ID and Post_ID.
    public function reactToPostByPostIDAndBenutzerID($p_id, $b_id)
    {
        $sql = "INSERT INTO Reaktion (Post_ID, Benutzer_ID) VALUES ('$p_id', '$b_id')";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_free_statement($statement);
    }

    //Returns the number of Reaktion per Post.
    public function getReaktionAmountByPostIDAndBenutzerID($p_id, $b_id)
    {
        $sql = "SELECT * FROM Reaktion WHERE Post_ID = '$p_id' AND Benutzer_ID = '$b_id'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        $res_array = array();
        while (oci_fetch_array($statement)) {
            $res = oci_result($statement, 'POST_ID');
            array_push($res_array, $res);
        }
        oci_free_statement($statement);
        if (count($res_array) > 0)
            return false;
        return true;
    }

    //Inserts a new TextPost with certain Text_ and Benutzer.
    public function addNewPost($text, $user)
    {
        $sql = "INSERT INTO TextPost (Text_, Benutzer) VALUES ('$text', '$user')";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_free_statement($statement);
    }

    //Inserts an entry into hat. this is necessary to keep TestPost, Benutzer and Wall connected and related.
    public function addRelationToHat($p_id, $b_id, $w_id)
    {
        $sql = "INSERT INTO hat (Post_ID, Benutzer_ID, Wall_ID) VALUES ('$p_id', '$b_id', '$w_id')";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_free_statement($statement);
    }

    //Returns the amount of TotalPosts that exist in the Network.
    public function getNumberTotalPosts()
    {
        $sql = "SELECT * FROM SocialMedia WHERE App_ID = '1'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_fetch($statement);
        $res = oci_result($statement, 'TOTALPOSTS');
        oci_free_statement($statement);
        return $res;
    }

    //Updates the status of a Benutzer_ID to online.
    public function goOnlineByBenutzerID($id)
    {
        $sql = "UPDATE Benutzer SET Online_='online' WHERE Benutzer_ID='$id'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_free_statement($statement);
    }

    //Updates the status of a Benutzer_ID to offline.
    public function goOfflineByBenutzerID($id)
    {
        $sql = "UPDATE Benutzer SET Online_='offline' WHERE Benutzer_ID='$id'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_free_statement($statement);
    }

    //Returns if a Benutzer_ID is online or offline.
    public function getOnlineStatusByBenutzerID($id)
    {
        $sql = "SELECT * FROM Benutzer WHERE Benutzer_ID='{$id}'";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_fetch($statement);
        $res = oci_result($statement, 'ONLINE_');
        oci_free_statement($statement);
        return $res;
    }

    //Returns the amount of Posts a certain Benutzer_ID has. This calls a Stored Procedure.
    public function postsByUser($input)
    {
        $sql = "CALL postsByUser(:input, :amount)";
        $statement = oci_parse($this->conn, $sql);
        oci_bind_by_name($statement, ':input', $input);
        oci_bind_by_name($statement, ':amount', $res);
        oci_execute($statement);
        oci_free_statement($statement);
        return $res;
    }
}
