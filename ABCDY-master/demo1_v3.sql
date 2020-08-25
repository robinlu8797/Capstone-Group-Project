DROP DATABASE IF EXISTS demo;

Create DATABASE demo;
--
-- Table structure for table `users`
--

CREATE TABLE users (
  id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  username varchar(50) NOT NULL UNIQUE KEY,
  password varchar(255) NOT NULL,
  role varchar(7) DEFAULT NULL,
  last_login datetime default null,
  created_at datetime DEFAULT current_timestamp(),
  enabled bit NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO users (id, userName, password, role, created_at, enabled)  VALUES
(6,'Gus','$2y$10$YahQ8ZTseQz.e5LqurAUyOHZBxZp6vOUXnCISO8wDVykKD09p/llG','HR','2019-09-15 14:42:49',''),
(7,'Mike','$2y$10$oTBPR1f0EiUV1E/k8XbwIuCgKCB/CsDyPqlIvYzGt8Tt4sTzY5kJ.','Trainee','2019-09-15 14:44:50',''),
(8,'Jim','$2y$10$A3DgJZqI3ZyTCyaUqoIWquj6zK8yBeKXwCWu3oKC0G1JQZawKwG4m','Manager','2019-09-18 17:52:52',''),
(9,'Pam','$2y$10$igk4QL2CCtb2bOpt0/ELXeX4/LpQn6k6o.4Sp8KfoV0JZBQC0xE1y','HR','2019-09-18 19:01:58',''),
(10, 'DimaMG', '$2y$10$ncpUCxBflN4Uv53MwAO5sOF/srtZcsfmS3B1F875SFcaynUzu2pyq', 'Manager', '2019-12-07 18:02:40',''),
(11, 'DimaTR', '$2y$10$lGD7lJo2vuoYdcSLMe9pDuIl4/tM6Y0FLg39r1P8YUCRLRXAdpk5m', 'Trainee', '2019-12-07 18:10:53',''),
(12, 'dimaHR', '$2y$10$MsGPM3.9bV/qE8TCCKUsQeZaAlUcR/qVRFozgxHVYpL5AyVePt1uO', 'HR', '2019-12-08 12:53:48',''),
(96,'test_HR','$2y$10$alrR3SB9ljJb8NeRuncb6uE/A1N4QfX7PzlnSdzZH5pzVNmgD8WQK','HR','2019-10-05 18:03:21',''),
(97,'test_Manager','$2y$10$CpOu1goK.qZXotikZUC.9.8Fk1FC.r.5om5XYVN/HL3gQPy5EXB0G','Manager','2019-10-05 18:03:51',''),
(98,'test_Trainee','$2y$10$T4ZW.2GgRP3SI/EMZ9UnrOWFmD9V6HX/0cT7EBPaNsVgdsGXTCduy','Trainee','2019-10-05 18:04:43',''),
(99,'test_Manager2','$2y$10$6IuMjZK2owJYVZZ/mLtmVuI.mLdvYdL9Yia1jPOC3QYQNXaiM7BpG','Manager','2019-10-09 15:13:05','');

CREATE TABLE user_info (
    user_id int NOT NULL PRIMARY KEY,
    lastName varchar(50) NOT NULL,
    firstName varchar(50) NOT NULL,
    address varchar(150) NOT NULL,
    city varchar(50) NOT NULL,
    state varchar(50) NOT NULL,
    zip varchar(50) NOT NULL,
    email varchar(150) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)

);
INSERT INTO user_info VALUES (96,'Last_Name_test_HR','First_Name_test_HR','Address test_HR','City test_HR','mn','12345','test_HR@test_HR.com'),
(97,'Last_Name_test_Manager','First_Name_test_Manager','Address test_Manager','City test_HR','mn','12345','test_Manager@test_Manager.com'),
(98,'Last_Name_test_Trainee','First_Name_test_Trainee','Address test_Trainee','City test_HR','mn','55345','test_Trainee@test_Trainee.com'),
(99,'test_Manager2','test_Manager2','Address test_Manager2','City test_Manager2','MN','12345','test_Manager2@test_Manager2.com');

CREATE TABLE training (
    training_id int PRIMARY KEY AUTO_INCREMENT,
    created_by int NOT NULL,
    training_title varchar(255) NOT NULL,
    create_dt  datetime DEFAULT current_timestamp(),
    FOREIGN KEY (created_by) REFERENCES users(id)
);


CREATE TABLE training_document(
    training_doc_id int PRIMARY KEY AUTO_INCREMENT,
    training_id int NOT NULL,
    training_doc_text text,
    create_dt datetime DEFAULT current_timestamp(),
    FOREIGN KEY (training_id) REFERENCES training(training_id)
);

CREATE TABLE training_link (
	training_link_id int PRIMARY KEY AUTO_INCREMENT,
	training_id int NOT NULL,
	training_link varchar(255) NOT NULL,
	training_link_type varchar(2) NOT NULL,
	create_dt datetime DEFAULT current_timestamp(),
	FOREIGN KEY (training_id) REFERENCES training(training_id)
);


CREATE TABLE test (
	test_id int PRIMARY KEY AUTO_INCREMENT,
	test_title varchar(255) NOT NULL,
 	created_by int NOT NULL,
	create_dt datetime DEFAULT current_timestamp(),
	FOREIGN KEY (created_by) REFERENCES users(id)
);
CREATE TABLE training_assigned (
    training_assigned_id int PRIMARY KEY AUTO_INCREMENT,
    training_id int default NULL,
    test_id int default NULL,
    assigned_by int NOT NULL,
    assigned_user_id int NOT NULL,
    assigned_dt datetime NOT NULL DEFAULT current_timestamp(),
    completed_dt datetime DEFAULT null,
    FOREIGN KEY (training_id) REFERENCES training(training_id),
    FOREIGN KEY (assigned_by) REFERENCES users(id),
    FOREIGN KEY (assigned_user_id) REFERENCES users(id),
    FOREIGN KEY (test_id) REFERENCES test(test_id)
);
CREATE TABLE test_question (
    question_id int PRIMARY KEY AUTO_INCREMENT,
    test_id int NOT NULL,
    question VARCHAR(255),
    FOREIGN KEY (test_id) REFERENCES test(test_id)
);
CREATE TABLE test_answer (
    answer_id int PRIMARY KEY AUTO_INCREMENT,
    question_id int NOT NULL,
    answer VARCHAR(255),
    correct int null,
    FOREIGN KEY (question_id) REFERENCES test_question(question_id)
);
CREATE TABLE test_scores (
    score_id int PRIMARY KEY AUTO_INCREMENT,
    test_id int NOT NULL,
    correct int,
    questions int,
    percent int,
    user_id int,
    FOREIGN KEY (test_id) REFERENCES test(test_id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
