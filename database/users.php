<?php
    class User{
        public string $username;
        public string $password;
        public string $email;
        public string $name;
        public string $pfp;
        
        public function __construct($username, $password, $email, $name){
            $this->username = $username;
            $this->password = $password;
            $this->email = $email;
            $this->name = $name;
        }

        static public function getUser($db, $username){
            $stmt = $db->prepare('SELECT * FROM User WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch();
        }

        static public function getEmail($db, $email){
            $stmt = $db->prepare('SELECT * FROM User WHERE email = :email');
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch();
        }

        static public function getUserEmail($db, $username){
            $stmt = $db->prepare('SELECT email FROM user WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch()['email'];
        }

        static public function changeEmail($db, $username, $new_email){
            $stmt = $db->prepare('UPDATE user SET email = :new_email WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':new_email', $new_email);
            $stmt->execute();
        }

        static public function changePassword($db, $username, $new_psw){
            $hashed_pw = password_hash($new_psw, PASSWORD_DEFAULT, ['cost' => 10]);
            $stmt = $db->prepare('UPDATE user SET password = :new_psw WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':new_psw', $hashed_pw);
            $stmt->execute();
        }

        static public function insertUser(PDO $db, User $user){
            
            $hashed_pw = password_hash($user->password, PASSWORD_DEFAULT, ['cost' => 10]);

            $searched_user = User::getUser($db, $user->username);
            if ($searched_user != null){return "The username provided is already in use, please use another";}

            $stmt = $db->prepare('INSERT INTO User (username, name, password, email) VALUES (?, ?, ?, ?)');
            $stmt->execute(array($user->username, $user->name, $hashed_pw, strtolower($user->email)));
            $stmt = $db->prepare('INSERT INTO Client (client_username) VALUES (?)');
            $stmt->execute(array($user->username));

        }
        static function getName(PDO $db, $username){
            $stmt = $db->prepare('SELECT name FROM User WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch()['name'];
        }
        
        public function deleteUser($username){
            global $db;
            $stmt = $db->prepare('DELETE FROM User WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
        }
        
        public function editUserProfile($username, $name, $email){
            global $db;
            $stmt = $db->prepare('UPDATE User SET name = :name, email = :email WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
        }
        
    
        public function getClient($username){
            global $db;
            $stmt = $db->prepare('SELECT * FROM Client WHERE client_username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch();
        }
        
        public function getAgent($username){
            global $db;
            $stmt = $db->prepare('SELECT * FROM Agent WHERE agent_username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch();
        }
        
        public function getAdmin($username){
            global $db;
            $stmt = $db->prepare('SELECT * FROM Admin WHERE admin_username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch();
        }

        static public function getUserUsernamePassword($username, $password) : ?User{
            global $db;
            $stmt = $db->prepare('
                SELECT * FROM User WHERE lower(username) = ?
            ');

            $stmt->execute(array(strtolower($username)));
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])){
                return new User(
                    $user['username'],
                    $user['password'],
                    $user['email'],
                    $user['name']
                );
            }
            else {
                return null;
            }
        }

        static public function checkPassword($pwd, &$errors): bool{
            $errors_init = $errors;

            if (strlen($pwd) < 8) {
                $errors[] = "Password too short!";
            }

            if (!preg_match("#[0-9]+#", $pwd)) {
                $errors[] = "Password must include at least one number!";
            }

            if (!preg_match("#[a-zA-Z]+#", $pwd)) {
                $errors[] = "Password must include at least one letter!";
            }

            return ($errors == $errors_init);
        }

        static public function checkUsername($username, &$errors): bool {
            $errors_init = $errors;

            if (strlen($username) > 11) {
                $errors[] = "Username must not exceed 11 characters!";
            }

            if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
                $errors[] = "Username must only contain letters and numbers!";
            }

            return ($errors == $errors_init);
        }

        static public function checkEmail($email, &$errors): bool {
            $errors_init = $errors;

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format!";
            }

            return ($errors == $errors_init);
        }

        static public function checkName($name, &$errors): bool {
            $errors_init = $errors;

            if (strlen($name) > 50) {
                $errors[] = "Name must not exceed 50 characters!";
            }

            if (!preg_match("/^[a-zA-Z\sÀ-ÿ]+$/u", $name)) {
                $errors[] = "Name must only contain letters, spaces, and accents!";
            }

            return ($errors == $errors_init);
        }

        static public function sameUName($db, $username, $newUName): bool{
            $stmt = $db->prepare('SELECT username FROM user WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $result = $stmt->fetch()['username'];
            return $result == $newUName;
        }

        static public function changeName($db, $username, $newName){
            $stmt = $db->prepare('UPDATE user SET name = :newName WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':newName', $newName);
            $stmt->execute();
        }


        static public function changeUName($db, $username, $newUName){
            $stmt = $db->prepare('UPDATE user SET username = :newUName WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':newUName', $newUName);
            $stmt->execute();
            $stmt = $db->prepare('UPDATE client SET client_username = :newUName WHERE client_username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':newUName', $newUName);
            $stmt->execute();
            $stmt = $db->prepare('UPDATE agent SET agent_username = :newUName WHERE agent_username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':newUName', $newUName);
            $stmt->execute();
            $stmt = $db->prepare('UPDATE admin SET admin_username = :newUName WHERE admin_username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':newUName', $newUName);
            $stmt->execute();
            $stmt = $db->prepare('UPDATE Link_departments SET username = :newUName WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':newUName', $newUName);
            $stmt->execute();
            $stmt = $db->prepare('UPDATE Ticket SET author_username = :newUName WHERE author_username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':newUName', $newUName);
            $stmt->execute();
            $stmt = $db->prepare('UPDATE Ticket SET agent_username = :newUName WHERE agent_username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':newUName', $newUName);
            $stmt->execute();
            $stmt = $db->prepare('UPDATE Comment SET username = :newUName WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':newUName', $newUName);
            $stmt->execute();
        }
        static public function getPfp(PDO $db, string $username){
            $stmt = $db->prepare('SELECT image_url FROM User WHERE username=:username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch()['image_url'];
        }

        static public function isAgent($db, $username): bool{
            $stmt = $db->prepare('SELECT * FROM agent WHERE agent_username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch() != null;
        }
        static public function isAdmin($db, $username): bool{
            $stmt = $db->prepare('SELECT * FROM admin WHERE admin_username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch() != null;
        }

        static public function getAgentsInfo($db): array{
            $stmt = $db->prepare('SELECT * FROM Agent JOIN User on username = agent_username');
            $stmt->execute();
            return $stmt->fetchAll();
        }

        static public function getAllAgents($db): array{
            $stmt = $db->prepare('SELECT * FROM agent');
            $stmt->execute();
            return $stmt->fetchAll();
        }

        static public function getAgentsByTicketDepartment($db, $ticket_id): array{
            $stmt = $db->prepare('SELECT A.agent_username
                                    FROM Ticket T
                                    INNER JOIN Department D ON T.department_id = D.id
                                    INNER JOIN Link_departments LD ON D.id = LD.department_id
                                    INNER JOIN Agent A ON LD.username = A.agent_username
                                    WHERE T.id = :ticket_id
                                    ');
            $stmt->bindParam(':ticket_id', $ticket_id);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        static public function getAgentsByDepartment($db, $department_id): array{
            $stmt = $db->prepare('SELECT A.agent_username
                                    FROM Department D
                                    INNER JOIN Link_departments LD ON D.id = LD.department_id
                                    INNER JOIN Agent A ON LD.username = A.agent_username
                                    WHERE D.id = :department_id
                                    ');
            $stmt->bindParam(':department_id', $department_id);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        static public function promoteUser($db, $username) : bool{
            //verify if user is in admin table
            $search1 = $db->prepare('SELECT * FROM admin WHERE admin_username = :username');
            $search1->bindParam(':username', $username);
            $search1->execute();

            if ($search1->fetch() != null)return false;

            //verify if user is in agent table
            $search2 = $db->prepare('SELECT * FROM agent WHERE agent_username = :username');
            $search2->bindParam(':username', $username);
            $search2->execute();

            if ($search2->fetch() != null){
                //insert user into admin table
                $stmt = $db->prepare('INSERT INTO admin (admin_username) VALUES (:username)');
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                return true;
            }

            //insert user into agent table
            $stmt = $db->prepare('INSERT INTO agent (agent_username) VALUES (:username)');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return true;
        }

        static public function demoteUser($db, $username): bool{
            //check if user is admin
            $search1 = $db->prepare('SELECT * FROM admin WHERE admin_username = :username');
            $search1->bindParam(':username', $username);
            $search1->execute();

            if ($search1->fetch() != null){
                $stmt = $db->prepare('DELETE FROM admin WHERE admin_username = :username');
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                return true;
            }

            //check if user is agent
            $search2 = $db->prepare('SELECT * FROM agent WHERE agent_username = :username');
            $search2->bindParam(':username', $username);
            $search2->execute();

            if ($search2->fetch() != null){
                $stmt = $db->prepare('DELETE FROM agent WHERE agent_username = :username');
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                return true;
            }

            return false;
        }

        static public function getUsername($db, $username){
            $stmt = $db->prepare('SELECT username FROM user WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch()['username'];
        }

        static public function assignAgentToDepartment($db, $username, $department_id){
            if (User::isAgent($db, $username) || User::isAdmin($db, $username)){
                $search = $db->prepare('SELECT * FROM link_departments WHERE username = :username AND department_id = :department_id');
                $search->bindParam(':username', $username);
                $search->bindParam(':department_id', $department_id);
                $search->execute();

                if ($search->fetch() != null) return;

                $stmt = $db->prepare('INSERT INTO link_departments (username, department_id) VALUES (:username, :department_id)');
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':department_id', $department_id);
                $stmt->execute();
            }
        }

        static public function removeAgentFromDepartment($db, $username, $department_id){
            $stmt = $db->prepare('DELETE FROM link_departments WHERE username = :username AND department_id = :department_id');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':department_id', $department_id);
            $stmt->execute();
            }
        }
?>
