<?php
require_once '../utils/misc.php';
require_once '../database/users.php';

class ticket
{

    static public function getClientTickets($db, $username)
    {
        $stmt = $db->prepare('SELECT * FROM ticket WHERE author_username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    static public function getTicketById($db, $id)
    {
        $stmt = $db->prepare('SELECT * FROM ticket WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }


    static public function getStatus($db, $status)
    {
        $stmt = $db->prepare('SELECT name FROM Statuses WHERE id = :status');
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    static public function addResponse($db, $ticketId, $username, $content)
    {
        $getLastId = $db->prepare('SELECT id FROM comment ORDER BY id DESC LIMIT 1');
        $getLastId->execute();
        $lastId = $getLastId->fetch()['id'];
        $id = $lastId + 1;
        $content = trim($content);
        $stmt = $db->prepare('INSERT INTO comment (id, ticket_id, username, content) VALUES (:id,:ticketId,:username, :content)');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':ticketId', $ticketId);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':content', $content);
        $stmt->execute();
    }


    static public function addTicket($db, $author, $department, $subject, $content, $hashtags, $documents)
    {

        $getLastId = $db->prepare('SELECT id FROM Ticket ORDER BY id DESC LIMIT 1');
        $getLastId->execute();
        $lastId = $getLastId->fetch()['id'];
        $id = $lastId + 1;


        $stmt = $db->prepare('INSERT INTO Ticket (id, author_username, department_id, subject, content, status) VALUES (:id, :author, :department, :subject, :content, 0)');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':content', $content);
        $stmt->execute();

        $seperatedHashtags = explode(' ', $hashtags);
        $hashtagsArray = array_filter($seperatedHashtags, function ($hashtag) {
            return $hashtag !== '';
        });

        foreach ($hashtagsArray as $ht) {
            Misc::addHashtagToTicket($db, $ht, $id);
        }

        foreach ($documents as $doc) {
            Misc::addDocumentToTicket($db, $doc, $id);
        }
    }

    static public function getTicketHashtagNames(PDO $db, int $ticketId): array
    {
        $stmt = $db->prepare("SELECT h.name FROM Link_hashtags lh JOIN Hashtags h ON lh.hashtag_id = h.id WHERE lh.ticket_id = :ticket_id");
        $stmt->bindValue(":ticket_id", $ticketId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    static public function getStatusName(PDO $db, int $statusId): string
    {
        $stmt = $db->prepare("SELECT name FROM Statuses WHERE id = :status_id");
        $stmt->bindValue(":status_id", $statusId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    static public function getStatusId(PDO $db, string $statusName): int
    {
        $stmt = $db->prepare("SELECT id FROM Statuses WHERE name = :status_name");
        $stmt->bindValue(":status_name", $statusName);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    static public function getDepartmentId(PDO $db, string $departmentName): int
    {
        $stmt = $db->prepare("SELECT id FROM Department WHERE name = :department_name");
        $stmt->bindValue(":department_name", $departmentName);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    static public function getDepartmentName(PDO $db, int $departmentId): string
    {
        $stmt = $db->prepare("SELECT name FROM Department WHERE id = :department_id");
        $stmt->bindValue(":department_id", $departmentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    static public function getPriorityName(PDO $db, int $priorityId): string
    {
        $stmt = $db->prepare("SELECT name FROM Priority WHERE id = :priority_id");
        $stmt->bindValue(":priority_id", $priorityId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    static public function getPriorityId(PDO $db, string $priorityName): int
    {
        $stmt = $db->prepare("SELECT id FROM Priority WHERE name = :priority_name");
        $stmt->bindValue(":priority_name", $priorityName);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    static public function getAllStatuses(PDO $db): array
    {
        $stmt = $db->prepare("SELECT * FROM Statuses");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    static public function getAllPriorities(PDO $db): array
    {
        $stmt = $db->prepare("SELECT * FROM Priority");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    static public function getAllDepartments(PDO $db): array
    {
        $stmt = $db->prepare("SELECT * FROM Department");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    static public function getAllFromLinkDepartment(PDO $db): array
    {
        $stmt = $db->prepare('SELECT * FROM link_departments');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    static public function getAllHashtags(PDO $db): array
    {
        $stmt = $db->prepare("SELECT name FROM Hashtags");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    static public function ticketHasHashtag(PDO $db, int $ticketId, string $hashtagName): bool
    {
        $stmt = $db->prepare("SELECT COUNT(*) FROM Link_hashtags lh JOIN Hashtags h ON lh.hashtag_id = h.id WHERE lh.ticket_id = :ticket_id AND h.name = :hashtag_name");
        $stmt->bindValue(":ticket_id", $ticketId);
        $stmt->bindValue(":hashtag_name", $hashtagName);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    static public function removeHashtagOfTicket(PDO $db, int $ticketId, string $hashtagName): void
    {
        $stmt = $db->prepare("DELETE FROM Link_hashtags WHERE ticket_id = :ticket_id AND hashtag_id = (SELECT id FROM Hashtags WHERE name = :hashtag_name)");
        $stmt->bindValue(":ticket_id", $ticketId);
        $stmt->bindValue(":hashtag_name", $hashtagName);
        $stmt->execute();
    }

    static public function hashtagIdExist(PDO $db, string $hashtagName): bool
    {
        $stmt = $db->prepare("SELECT COUNT(*) FROM Hashtags WHERE name = :hashtag_name");
        $stmt->bindValue(":hashtag_name", $hashtagName);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    static public function getTicketResponses(PDO $db, int $ticketId): array
    {
        $stmt = $db->prepare("SELECT * FROM Comment WHERE ticket_id = :ticket_id");
        $stmt->bindValue(":ticket_id", $ticketId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    static public function changeStatus($db, $ticketId, $status)
    {
        $stmt = $db->prepare('UPDATE Ticket SET status = :status WHERE id = :ticketId');
        $stmt->bindParam(':ticketId', $ticketId);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        ticket::ticketLog($db, $ticketId, "Status changed to " . ticket::getStatusName($db, $status));
    }

    static public function getTicketsByStatus($db, $status)
    {
        $stmt = $db->prepare('SELECT * FROM Ticket WHERE status = :status');
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    static public function changeAgent($db, $ticketId, $agent)
    {
        $stmt = $db->prepare('UPDATE Ticket SET agent_username = :agent WHERE id = :ticketId');
        $stmt->bindParam(':ticketId', $ticketId);
        $stmt->bindParam(':agent', $agent);
        $stmt->execute();
        ticket::ticketLog($db, $ticketId, "Agent changed to " . User::getUsername($db, $agent));
    }

    static public function changeDepartment($db, $ticketId, $department)
    {
        $stmt = $db->prepare('UPDATE Ticket SET department_id = :department WHERE id = :ticketId');
        $stmt->bindParam(':ticketId', $ticketId);
        $stmt->bindParam(':department', $department);
        $stmt->execute();
        ticket::ticketLog($db, $ticketId, "Department changed to " . ticket::getDepartmentName($db, $department));
    }

    static public function changePriority($db, $ticketId, $priority)
    {
        $stmt = $db->prepare('UPDATE Ticket SET priority = :priority WHERE id = :ticketId');
        $stmt->bindParam(':ticketId', $ticketId);
        $stmt->bindParam(':priority', $priority);
        $stmt->execute();
        ticket::ticketLog($db, $ticketId, "Priority changed to " . ticket::getPriorityName($db, $priority));
    }

    static public function getDocument($db, $ticket): array
    {
        $stmt = $db->prepare('SELECT * FROM Link_documents WHERE ticket_id = :ticketId');
        $stmt->bindParam(':ticketId', $ticket);
        $stmt->execute();

        $returnFiles = $stmt->fetchAll();
        $returnPaths = array();

        for ($i = 0; $i < count($returnFiles); $i++) {
            $doc = $returnFiles[$i];

            $stmt = $db->prepare('SELECT * FROM document WHERE id = :docId');
            $stmt->bindParam(':docId', $doc['document_id']);
            $stmt->execute();
            $returnPaths[$i] = $stmt->fetch()['url'];
        }
        return $returnPaths;
    }

    static public function getAllTickets($db): array
    {
        $stmt = $db->prepare('SELECT * FROM Ticket');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    static public function getTickets($db, $username): array
    {
        if (user::isAgent($db, $username)) {
            return self::getAllTickets($db);
        } else {
            return ticket::getClientTickets($db, $username);
        }
    }

    static public function canModifyTicket($db, $username, $ticket): bool
    {
        if ((user::isAgent($db, $username) && (($ticket['agent_username'] == $username) || ($ticket['agent_username'] == null))) || (user::isAdmin($db, $username))) {
            return true;
        } else {
            return false;
        }
    }


    static public function ticketLog($db, $ticketId, $content): bool
    {
        $stmt = $db->prepare('INSERT INTO TicketLog (ticket_id, content) VALUES (:ticketId, :content)');
        $stmt->bindParam(':ticketId', $ticketId);
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    }

    static public function getLogs($db, $ticketId): array
    {
        $stmt = $db->prepare('SELECT * FROM TicketLog WHERE ticket_id = :ticketId');
        $stmt->bindParam(':ticketId', $ticketId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    static public function deleteTicket($db, $ticketId): bool
    {
        $stmt = $db->prepare('DELETE FROM Ticket WHERE id = :ticketId');
        $stmt->bindParam(':ticketId', $ticketId);
        $stmt->execute();

        $stmt = $db->prepare('DELETE FROM TicketLog WHERE ticket_id = :ticketId');
        $stmt->bindParam(':ticketId', $ticketId);
        $stmt->execute();

        $stmt = $db->prepare('DELETE FROM Link_hashtags WHERE ticket_id = :ticketId');
        $stmt->bindParam(':ticketId', $ticketId);
        $stmt->execute();

        $stmt = $db->prepare('DELETE FROM Link_documents WHERE ticket_id = :ticketId');
        $stmt->bindParam(':ticketId', $ticketId);
        $stmt->execute();

        $stmt = $db->prepare('DELETE FROM Comment WHERE ticket_id = :ticketId');
        $stmt->bindParam(':ticketId', $ticketId);
        return $stmt->execute();
    }
}
