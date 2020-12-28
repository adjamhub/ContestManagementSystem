-- Andrea Diamantini
-- 10 dicembre 2016
-- Database per il "mio" CMS ;)
-- Versione 0.1.0


-- Just to be sure... 
-- clean all up :)
DROP DATABASE CMS_DB;

-- DATABASE CMS_DB
-- Create DB CMS_DB && grant use to cms_user/cms_pass user
CREATE DATABASE if not exists CMS_DB;
GRANT ALL on CMS_DB.* TO 'cms_user'@'%' IDENTIFIED BY 'cms_pass';
USE CMS_DB;


-- Table tbl_class
-- An identified group of students
CREATE TABLE if not exists tbl_class (
    id varchar(5) NOT NULL,
    year int NOT NULL,
    section varchar (4) NOT NULL,
    PRIMARY KEY (id)
)  ENGINE = "InnoDB" DEFAULT CHARACTER SET=utf8; 


-- easy class in ;)
INSERT INTO `tbl_class` ( `id` , `year` , `section` ) VALUES 
( '3AS', 3 , 'AS' ),
( '3BS', 3 , 'BS' ),
( '4AS', 4 , 'AS' ),
( '4BS', 4 , 'BS' );


-- Table tbl_student
-- One student from a class, with user a pass
CREATE TABLE if not exists tbl_student (
  user varchar(10) NOT NULL,
  pass varchar(40) NOT NULL,
  name varchar(30) NOT NULL,
  surname varchar(30) NOT NULL,
  class varchar(5) NOT NULL,
  PRIMARY KEY (user),
  FOREIGN KEY(class) REFERENCES tbl_class(id)
)  ENGINE = "InnoDB" DEFAULT CHARACTER SET=utf8; 


-- users to check if things go well in the classrooms
INSERT INTO `tbl_student` ( `user` , `pass` , `name` , `surname` , `class` ) VALUES 
( 's03as', 's03as', 'Ciccio'   , 'Filippo'  , '3AS' ),
( 's03bs', 's03bs', 'Pippo'    , 'Canone'   , '3BS' ),
( 's04as', 's04as', 'Pluto'    , 'Canino'   , '4AS' ),
( 's04bs', 's04bs', 'Paperino' , 'Paolino'  , '4BS' );


-- Contest
-- Collection of tasks for the students
-- Should be a ClassWork or something like that
CREATE TABLE if not exists tbl_contest (
  id int NOT NULL auto_increment,
  description varchar(40),
  date_start datetime NOT NULL,
  date_finish datetime NOT NULL,
  is_enabled bit NOT NULL default 1,
  class varchar(5) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY(class) REFERENCES tbl_class(id)
)  ENGINE = "InnoDB" DEFAULT CHARACTER SET=utf8 AUTO_INCREMENT=1; 


-- topic
-- An argument for a task
CREATE TABLE if not exists tbl_topic (
  keyword varchar(15) NOT NULL,
  argument varchar(30) NOT NULL,
  PRIMARY KEY (keyword)
)  ENGINE = "InnoDB" DEFAULT CHARACTER SET=utf8; 


-- easy data in ;)
INSERT INTO `tbl_topic` ( `keyword` , `argument` ) VALUES 
( '00iniziali'   , 'Iniziali'                     ),
( '01variabili'  , 'Variabili e operatori'        ),
( '02ifelse'     , 'Istruzione if-else'           ),
( '03switchcase' , 'Istruzione switch-case'       ),
( '04dowhile'    , 'Iterazioni POST-condizionali' ),
( '05while'      , 'Iterazioni PRE-condizionali'  ),
( '06for'        , 'Ciclo for'     ),
( '07cicli'      , 'Cicli'         ),
( '08array'      , 'Array'         ),
( '09struct'     , 'Struct'        ),
( '10file'       , 'File di testo' ),
( '11pointers'   , 'Puntatori'     ),
( '12functions'  , 'Funzioni'      ),
( '13liste'      , 'Liste'         ),
( '14grafi'      , 'Grafi'         );


-- task
-- A problem to solve :o
CREATE TABLE if not exists tbl_task (
  keyword varchar(30) NOT NULL,
  topic varchar(15) NOT NULL,
  PRIMARY KEY  (keyword),
  FOREIGN KEY(topic) REFERENCES tbl_topic(keyword)
)  ENGINE = "InnoDB" DEFAULT CHARACTER SET=utf8; 



-- contest/task
-- M to N association
CREATE TABLE if not exists tbl_cont_task (
  contest int NOT NULL,
  task varchar(30) NOT NULL,
  PRIMARY KEY(contest, task),
  FOREIGN KEY(contest) REFERENCES tbl_contest(id),
  FOREIGN KEY(task) REFERENCES tbl_task(keyword)
)  ENGINE = "InnoDB" DEFAULT CHARACTER SET=utf8; 


-- delivery
-- a task delivered by one student
CREATE TABLE if not exists tbl_delivery (
  id int NOT NULL auto_increment,
  deliverDate datetime NOT NULL,
  student varchar(10) NOT NULL,
  task varchar(30) NOT NULL,
  contest int NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(student) REFERENCES tbl_student(user),
  FOREIGN KEY(task) REFERENCES tbl_task(keyword),
  FOREIGN KEY(contest) REFERENCES tbl_contest(id)
)  ENGINE = "InnoDB" DEFAULT CHARACTER SET=utf8 AUTO_INCREMENT=1; 


--------------------------------------------------------------------------
