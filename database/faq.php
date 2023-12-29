<?php 
    require_once "connection.db.php";

    class FAQ {
        private $db;

        public function __construct() {
            $this->db = getDatabaseConnection();
        }

        public function getAllFAQ() {
            $stmt = $this->db->prepare('SELECT * FROM faq');
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public function getFAQById($id) {
            $stmt = $this->db->prepare('SELECT * FROM faq WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch();
        }

        public function addFAQ($question, $answer) {
            $getLastId = $this->db->prepare('SELECT id FROM faq ORDER BY id DESC LIMIT 1');
            $getLastId->execute();
            $lastId = $getLastId->fetch()['id'];
            $id = $lastId + 1;

            $stmt = $this->db->prepare('INSERT INTO faq (id, question, answer) VALUES (:id,:question,:answer)');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':question', $question);
            $stmt->bindParam(':answer', $answer);
            $stmt->execute();
        }

        public function updateFAQ($id, $question, $answer) {
            $stmt = $this->db->prepare('UPDATE faq SET question = :question, answer = :answer WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':question', $question);
            $stmt->bindParam(':answer', $answer);
            $stmt->execute();
        }

        public function deleteFAQ($id) {
            $stmt = $this->db->prepare('DELETE FROM faq WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
    }