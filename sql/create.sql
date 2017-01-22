#Create Tables
CREATE TABLE Movie(id INT PRIMARY KEY, title VARCHAR(100), year INT, rating VARCHAR(10), company VARCHAR(50), CHECK(title IS NOT NULL AND year IS NOT NULL AND rating IS NOT NULL)) ENGINE = INNODB;
CREATE TABLE Actor(id INT PRIMARY KEY, last VARCHAR(20), first VARCHAR(20), sex VARCHAR(6), dob date, dod date, CHECK(first IS NOT NULL AND dob IS NOT NULL)) ENGINE = INNODB;
CREATE TABLE Director(id INT PRIMARY KEY, last VARCHAR(20), first VARCHAR(2), dob date, dod date) ENGINE = INNODB;
CREATE TABLE MovieGenre(mid INT, genre VARCHAR(20), PRIMARY KEY(mid, genre), FOREIGN KEY(mid) references Movie(id)) ENGINE = INNODB;
CREATE TABLE MovieDirector(mid INT, did INT, PRIMARY KEY(mid, did), FOREIGN KEY(mid) references Movie(id), FOREIGN KEY(did) references Director(id)) ENGINE = INNODB;
CREATE TABLE MovieActor(mid INT, aid INT, role VARCHAR(50), PRIMARY KEY(mid, aid), FOREIGN KEY(mid) references Movie(id), FOREIGN KEY(aid) references Actor(id)) ENGINE = INNODB;
CREATE TABLE Review(name VARCHAR(20), time datetime, mid INT, rating INT, comment VARCHAR(500), FOREIGN KEY(mid) references Movie(id)) ENGINE = INNODB;
CREATE TABLE MaxPersonID(id INT) ENGINE = INNODB;
CREATE TABLE MaxMovieID(id INT) ENGINE = INNODB;

