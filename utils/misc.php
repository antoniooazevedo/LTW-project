<?php
    declare(strict_types=1);

    class Misc{

        static function addHashtagToTicket($db, $hashtag, $ticketId){
            $search = $db->prepare('SELECT * FROM Hashtags WHERE name = :hashtag');
            $search->bindParam(':hashtag', $hashtag);
            $search->execute();
            $alrExists = $search->fetch();
            
            if (!$alrExists){
                $getLastId = $db->prepare('SELECT id FROM Hashtags ORDER BY id DESC LIMIT 1');
                $getLastId->execute();
                $lastId = $getLastId->fetch()['id'];
                $hashId = $lastId + 1;

                $add = $db->prepare('INSERT INTO Hashtags (id, name) VALUES (:id, :name)');
                $add->bindParam(':id', $hashId);
                $add->bindParam(':name', $hashtag);
                $add->execute();
            }
            else{
                $hashId = $alrExists['id'];
            }

            $verify = $db->prepare('SELECT * FROM Link_hashtags WHERE ticket_id = :ticket_id AND hashtag_id = :hashtag_id');
            $verify->bindParam(':ticket_id', $ticketId);
            $verify->bindParam(':hashtag_id', $hashId);
            $verify->execute();
            if ($verify->fetch()) return;

            $stmt = $db->prepare('INSERT INTO Link_hashtags (ticket_id, hashtag_id) VALUES (:ticket_id, :hashtag_id)');
            $stmt->bindParam(':ticket_id', $ticketId);
            $stmt->bindParam(':hashtag_id', $hashId);
            $stmt->execute();
        }

        static function addDocumentToTicket($db, $document, $ticketId){
            $getLastId = $db->prepare('SELECT id FROM Document ORDER BY id DESC LIMIT 1');
            $getLastId->execute();
            $lastId = $getLastId->fetch()['id'];
            $docId = $lastId + 1;

            $add = $db->prepare('INSERT INTO Document (id, url) VALUES (:id, :url)');
            $add->bindParam(':id', $docId);
            $add->bindParam(':url', $document);
            $add->execute();
            
            $stmt = $db->prepare('INSERT INTO Link_documents (ticket_id, document_id) VALUES (:ticket_id, :document_id)');
            $stmt->bindParam(':ticket_id', $ticketId);
            $stmt->bindParam(':document_id', $docId);
            $stmt->execute();
        }

        static function getDepartments($db){
            $stmt = $db->prepare('SELECT * FROM Department');
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        static function addDepartment($db, $name) :int {
            $search = $db->prepare('SELECT * FROM Department WHERE name = :name');
            $search->bindParam(':name', $name);
            $search->execute();
            $alrExists = $search->fetch();

            if (!$alrExists){
                $getLastId = $db->prepare('SELECT id FROM Department ORDER BY id DESC LIMIT 1');
                $getLastId->execute();
                $lastId = $getLastId->fetch()['id'];
                $id = $lastId + 1;
            }
            else{
                return -1;
            }

            $stmt2 = $db->prepare('INSERT INTO Department (id, name) VALUES (:id, :name)');
            $stmt2->bindParam(':id', $id);
            $stmt2->bindParam(':name', $name);
            $stmt2->execute();

            return intval($id, 10);
        }

        static function removeDepartment($db, $name){
            $stmt = $db->prepare('DELETE FROM Department WHERE name = :name');
            $stmt->bindParam(':name', $name);
            $stmt->execute();
        }

        static function linkDepartmentToTicket($db, $ticketId, $departmentId){
            $stmt = $db->prepare('INSERT INTO Link_departments (ticket_id, department_id) VALUES (:ticket_id, :department_id)');
            $stmt->bindParam(':ticket_id', $ticketId);
            $stmt->bindParam(':department_id', $departmentId);
            $stmt->execute();
        }

        static function changeTicketStatus($db, $ticketId, $statusId){
            $stmt = $db->prepare('UPDATE Ticket SET status = :status_id WHERE id = :ticket_id');
            $stmt->bindParam(':ticket_id', $ticketId);
            $stmt->bindParam(':status_id', $statusId);
            $stmt->execute();
        }
    }
?>