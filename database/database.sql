PRAGMA foreign_keys = ON;

DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS client;
DROP TABLE IF EXISTS agent;
DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS department;
DROP TABLE IF EXISTS link_departments;
DROP TABLE IF EXISTS statuses;
DROP TABLE IF EXISTS priority;
DROP TABLE IF EXISTS ticket;
DROP TABLE IF EXISTS hashtags;
DROP TABLE IF EXISTS link_hashtags;
DROP TABLE IF EXISTS faq;
DROP TABLE IF EXISTS document;
DROP TABLE IF EXISTS link_documents;
DROP TABLE IF EXISTS comment;

CREATE TABLE User (
  'username' varchar(255) NOT NULL,
  'name' varchar(255) NOT NULL,
  'password' varchar(255) NOT NULL,
  'email' varchar(255) NOT NULL,
  'image_url' varchar(255) NOT NULL DEFAULT '../images/default_user.png',
  PRIMARY KEY ('username')
);

CREATE TABLE Client(
  'client_username' varchar(255) NOT NULL,
  FOREIGN KEY ('client_username') REFERENCES 'User' ('username')
  PRIMARY KEY ('client_username')
);

CREATE TABLE Agent(
  'agent_username' varchar(255) NOT NULL,
  FOREIGN KEY ('agent_username') REFERENCES 'User' ('username')
  PRIMARY KEY ('agent_username')
);

CREATE TABLE Admin(
  'admin_username' varchar(255) NOT NULL,
  FOREIGN KEY ('admin_username') REFERENCES 'User' ('username')
  PRIMARY KEY ('admin_username')
);

CREATE TABLE Department (
  'id' int(6) NOT NULL,
  'name' varchar(255) NOT NULL,
  PRIMARY KEY ('id')
);

CREATE TABLE Link_departments(
  'department_id' int(6) NOT NULL,
  'username' varchar(255) NOT NULL,
  FOREIGN KEY ('department_id') REFERENCES 'department' ('id'),
  FOREIGN KEY ('username') REFERENCES 'User' ('username')
);

CREATE TABLE Statuses(
  'id' int(1) NOT NULL,
  'name' varchar(255) NOT NULL,
  PRIMARY KEY ('id')
);

CREATE TABLE Priority(
  'id' int(1) NOT NULL,
  'name' varchar(255) NOT NULL,
  PRIMARY KEY ('id')
);

CREATE TABLE Hashtags(
  'id' int(6) NOT NULL,
  'name' varchar(255) NOT NULL,
  PRIMARY KEY ('id')
);

CREATE TABLE Ticket (
  'id' int(6) NOT NULL,
  'author_username' varchar(255) NOT NULL,
  'department_id' int(11),
  'agent_username' varchar(255),
  'subject' varchar(255) NOT NULL,
  'content' text NOT NULL,
  'status' int(1) DEFAULT 0,
  'date' datetime DEFAULT CURRENT_TIMESTAMP,
  'priority' int(1) DEFAULT 0,
  PRIMARY KEY ('id'),
  FOREIGN KEY ('status') REFERENCES 'statuses' ('id')
  FOREIGN KEY ('author_username') REFERENCES 'User' ('username')
  FOREIGN KEY ('department_id') REFERENCES 'department' ('id')
  FOREIGN KEY ('agent_username') REFERENCES 'Agent' ('agent_username')
  FOREIGN KEY ('status') REFERENCES 'Statuses' ('id')
);

CREATE TABLE TicketLog (
  'id' int(6),
  'ticket_id' int(6) NOT NULL,
  'content' text NOT NULL,
  'date' datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ('id'),
  FOREIGN KEY ('ticket_id') REFERENCES 'ticket' ('id')
);


CREATE TABLE Link_hashtags(
  'ticket_id' int(6) NOT NULL,
  'hashtag_id' int(11) NOT NULL,
  FOREIGN KEY ('ticket_id') REFERENCES 'ticket' ('id'),
  FOREIGN KEY ('hashtag_id') REFERENCES 'hashtags' ('id')
);

CREATE TABLE Faq(
  'id' int(6) NOT NULL,
  'question' varchar(255) NOT NULL,
  'answer' text NOT NULL,
  PRIMARY KEY ('id')
);

CREATE TABLE Document(
  'id' int(6) NOT NULL,
  'url' varchar(255) NOT NULL,
  PRIMARY KEY ('id')
);

CREATE TABLE Link_documents(
  'ticket_id' int(6) NOT NULL,
  'document_id' int(6) NOT NULL,
  FOREIGN KEY ('ticket_id') REFERENCES 'ticket' ('id'),
  FOREIGN KEY ('document_id') REFERENCES 'document' ('id')
);

CREATE TABLE Comment(
  'id' int(6) NOT NULL,
  'ticket_id' int(6) NOT NULL,
  'username' varchar(255) NOT NULL,
  'content' text NOT NULL,
  'date' datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ('id'),
  FOREIGN KEY ('ticket_id') REFERENCES 'ticket' ('id'),
  FOREIGN KEY ('username') REFERENCES 'User' ('username')
);


INSERT INTO 'Department' ('id', 'name') VALUES
(1, 'Accounting'),
(2, 'Human Resources'),
(3, 'IT'),
(4, 'Marketing'),
(5, 'Sales');


INSERT INTO 'Statuses' ('id', 'name') VALUES
(0, 'Open'),
(1, 'Assigned'),
(2, 'Closed');

INSERT INTO 'Priority' ('id', 'name') VALUES
(0, 'None'),
(1, 'Low'),
(2, 'Medium'),
(3, 'High');

INSERT INTO 'Hashtags' ('id', 'name') VALUES
(1, 'relatable'),
(2, 'funny'),
(3, 'cool'),
(4, 'interesting'),
(5, 'awesome'),
(6, 'amazing'),
(7, 'wow'),
(8, 'nice'),
(9, 'great'),
(10, 'good');


INSERT INTO 'Faq' ('id', 'question', 'answer') VALUES
(1, 'How can I create a support ticket on your website?', 'You can create a support ticket by navigating over to the "Tickets" button at the top of your screen, and then selecting the "Create Ticket" button to be redirected to the respective ticket form.'),
(2, 'How do I assign a ticket to a specific agent or department?', 'You can select a department when creating your ticket by choosing one in the dropdown menu, however assigning agents can only be done by authorized users (Agents and Admins) after creating your ticket.'),
(3, 'Can I track the status of my support ticket online?', 'Yes, by navigating to the "Tickets" button at the top of your screen and then clicking the "Your tickets" button, you will be able to see all of your created tickets and their statuses.'),
(4, 'What is the purpose of using hashtags in support tickets?', 'It helps our team with filtering through certain subjects for easy organization, which leads to a more efficient work environment'),
(5, 'Can I communicate with the assigned agent directly for follow-up questions or clarifications?', 'You can add comments to your ticket, regarding updating information or asking further questions relative to the ticket subject. If agents need to contact you for updates or for you to provide further information, they will do so through the comment system of your specific ticket.');

