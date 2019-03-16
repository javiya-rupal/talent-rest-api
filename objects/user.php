<?php
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
    // create new user record
    function create() {
     
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    password = :password";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
     
        // bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
     
        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        // execute the query, also check if query was successful
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }
    
    // check if given email exist in the database
    function emailExists() {
     
        // query to check if email exists
        $query = "SELECT id, password
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";
     
        // prepare the query
        $stmt = $this->conn->prepare( $query );
     
        // sanitize
        $this->email = htmlspecialchars(strip_tags($this->email));
     
        // bind given email value
        $stmt->bindParam(1, $this->email);
     
        // execute the query
        $stmt->execute();
     
        // get number of rows
        $num = $stmt->rowCount();
     
        // return true if email exists else false will return
        if($num > 0){
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id']; 
            $this->password = $row['password'];            
            return true;
        }
        return false;
    }  

    // update a user record
    function updateCredentials(){
        // if password needs to be updated
        $password_set = !empty($this->password) ? " password = :password" : "";
     
        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . "
                SET                   
                    {$password_set}
                WHERE id = :id";

        // prepare the query
        $stmt = $this->conn->prepare($query);
          
        // hash the password before saving to database
        if(!empty($this->password)){
            $this->password=htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }
     
        // unique ID of record to be edited
        $stmt->bindParam(':id', $this->id);
     
        // execute the query
        if($stmt->execute()){
            return $this->getDetails();
        }     
        return false;
    }

    // get a user record
    function getDetails(){
        // Here is query to get all data for user from multiple tables
        //TODO: There are few tables still need to add
        $query = "SELECT * FROM " . $this->table_name . " u
                LEFT JOIN user_address ua on ua.user_id = u.id
                LEFT JOIN user_experience ue on ue.user_id = u.id
                LEFT JOIN user_cv uc on uc.user_id = u.id
                WHERE u.id = :id";

        // prepare the query
        $stmt = $this->conn->prepare($query);          
       
        // unique ID of record to be edited
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if($stmt->execute()){
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //TODO: I planned here to get all data with relation of user id from all the tables.
            //Currently due to time boundry I set it static and expect it to set as below structure
            //Address
            $address['thoroughfare'] = $row['thoroughfare'];
            $address['premise'] = $row['premise'];
            $address['postalCode'] = $row['postalCode'];
            $address['locality'] = $row['locality'];

            //Experience
            $experience = [['id' => '1', 'from' => '2001', 'to' => '2002', 'employer' => 'ABC coporation', 'work' => 'Trainee'], 
            ['id' => '2', 'from' => '2002', 'to' => '2004', 'employer' => 'XYZ coporation', 'work' => 'Junior Engineer'],
             ['id' => '3', 'from' => '2004', 'to' => '2015', 'employer' => 'Rocket Talent', 'work' => 'Software Developer']];
 
            //Match Making
            $matchMaking['jobSearch'] = "Active";
            $matchMaking['field'] =  ['105' => 'IT', '201' => 'Software', '206' => 'Law Firm', '307' => 'Accounting'];
            $matchMaking['locality'] = ['Europe', 'London', 'Germany'];
            $matchMaking['kindOfCompany'] = ['105' => 'IT', '201' => 'Software', '206' => 'Law Firm', '307' => 'Accounting'];
            $matchMaking['kindOfJobAd'] = ['105' => 'IT', '201' => 'Software', '206' => 'Law Firm', '307' => 'Accounting'];
           
            //CV
            $cv['status'] = $row['status'];
            $cv['universities'] = ["university-1", "university-2", "university-3"];
            $cv['phd'] = $row['phd'];
            $cv['stipendium'] = ['Stipendium-1', 'Stipendium-2', 'Stipendium-3', 'Stipendium-4'];
            $cv['abitur'] = $row['abitur'];
            $cv['birthday'] = $row['birthday'];
            $cv['experience'] = $experience;
            $cv['address'] = $address;
            $cv['salaryDesired'] = $row['salaryDesired'];

            //User Profile
            $userProfile['firstName'] = $row['firstname'];
            $userProfile['lastName'] = $row['lastname'];
            $userProfile['company'] = "abc";
            $userProfile['matchMaking'] = $matchMaking;
            $userProfile['cv'] = $cv;

            //User Details
            $userDetails['id'] = $row['id'];
            $userDetails['mail'] = $row['email'];            
            $userDetails['profile'] = $userProfile;

            return $userDetails;
        }
        return false;
    }

}