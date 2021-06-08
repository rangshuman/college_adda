CREATE DATABASE mgr_community_forum COLLATE utf8mb4_general_ci;

/*Creating the User table*/
CREATE TABLE mgr_community_forum.user (
    id INT(10) UNSIGNED AUTO_INCREMENT,
    first_name VARCHAR(200) NOT NULL,
    last_name VARCHAR(200) NOT NULL,
    reg_no BIGINT UNSIGNED NOT NULL PRIMARY KEY,
    admit_year INT(4) NOT NULL,
    email VARCHAR(50) NOT NULL,
    mobile VARCHAR(10) NOT NULL,
    role_id INT(2),
    password VARCHAR(32) NOT NULL,
    auth_token VARCHAR(32),
    login_time BIGINT,
    UNIQUE (id,reg_no)
);

CREATE TABLE mgr_community_forum.question (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reg_no_asker BIGINT UNSIGNED NOT NULL,
    q_title TEXT(300) NOT NULL,
    q_value LONGTEXT,
    upvote INT(10) UNSIGNED,
    downvote INT(10) UNSIGNED,
    UNIQUE (id)
);

CREATE TABLE mgr_community_forum.answer (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reg_no_submitter BIGINT UNSIGNED NOT NULL,
    q_id INT(10) NOT NULL,
    ans_value LONGTEXT NOT NULL,
    upvote INT(10) UNSIGNED,
    downvote INT(10) UNSIGNED,
    UNIQUE (id)
);

CREATE TABLE mgr_community_forum.roles (
    id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(30) NOT NULL,
    UNIQUE (id)
);

CREATE TABLE mgr_community_forum.votes (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reg_no BIGINT NOT NULL,
    vote_type VARCHAR(30) NOT NULL,
    q_id INT(10),
    ans_id INT(10),
    vote_value INT(2) NOT NULL
);

CREATE TABLE mgr_community_forum.notice (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    notice_raiser_reg_no BIGINT NOT NULL,
    notice_title TEXT(300) NOT NULL,
    notice_subject TEXT(300) NOT NULL,
    notice_value LONGTEXT,
);

/* Inserting default roles */
INSERT INTO mgr_community_forum.roles (role_name) VALUES ('Superadmin');
INSERT INTO mgr_community_forum.roles (role_name) VALUES ('Admin');
INSERT INTO mgr_community_forum.roles (role_name) VALUES ('Teacher');
INSERT INTO mgr_community_forum.roles (role_name) VALUES ('Student');

/* Inserting a default Admin user */
INSERT INTO mgr_community_forum.user (first_name, last_name, reg_no, admit_year, email, mobile, role_id, password) SELECT 'Super', 'Admin', 000000000000, 1998, 'admin@drmgrdu.ac.in', 9886754553, id, 'e6e061838856bf47e1de730719fb2609' FROM mgr_community_forum.roles WHERE role_name = 'Superadmin';