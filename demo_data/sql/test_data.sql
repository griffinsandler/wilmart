-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS 'gatechUser'@'localhost' IDENTIFIED BY 'gatech123';

DROP DATABASE IF EXISTS `cs6400_sm21_team49`; 
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_sm21_team49 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_sm21_team49;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_sm21_team49`.* TO 'gatechUser'@'localhost';
FLUSH PRIVILEGES;


CREATE TABLE `User`(
    Username varchar(50) NOT NULL,
    Password varchar(50) NOT NULL,
    Type varchar(50) NOT NULL,
    PRIMARY KEY(Username)
);


CREATE TABLE City (
    CityName varchar(50) NOT NULL,
    State varchar(50) NOT NULL,
    Population int NOT NULL,
    PRIMARY KEY(CityName, State)
);

CREATE TABLE Store(
    StoreID int NOT NULL,
    PhoneNumber varchar(50) NOT NULL,
    Address varchar(50) NOT NULL,
    WillsGrandShowcase boolean NOT NULL,
    CityName varchar(50) NOT NULL,
    State varchar(50) NOT NULL,
    FOREIGN KEY (CityName, State)
        REFERENCES `City` (CityName,State),
    PRIMARY KEY(StoreID)
);

CREATE TABLE `Business Day` (
    Date date NOT NULL,
    SavingsDay boolean NOT NULL,
    PercentDiscount float NOT NULL,
    PRIMARY KEY(Date)
);

CREATE TABLE Manufacturer (
    Name varchar(50) NOT NULL,
    PRIMARY KEY(Name)
);

CREATE TABLE Category (
    Name varchar(50) NOT NULL,
    PRIMARY KEY(Name)
);

CREATE TABLE Product (
    PID int NOT NULL,
    Price float NOT NULL,
    Name varchar(50) NOT NULL,
    Mname varchar(50) NOT NULL,
    PRIMARY KEY(PID),
    FOREIGN KEY (MName)
        REFERENCES Manufacturer (Name)
);

CREATE TABLE Manages (
    Username varchar(50) NOT NULL,
    StoreID int NOT NULL,
    FOREIGN KEY (Username) REFERENCES User (Username),
    FOREIGN KEY (StoreID) REFERENCES Store (StoreID),
    PRIMARY KEY(Username, StoreID)
);


CREATE TABLE `Discounted On` (
    PID int NOT NULL,
    Date date NOT NULL,
    DiscountPrice float NOT NULL,
    FOREIGN KEY (PID) REFERENCES Product (PID),
    FOREIGN KEY (Date) REFERENCES `Business Day` (Date),
    PRIMARY KEY(PID, Date)
);

CREATE TABLE IsInCategory (
    CatName varchar(50) NOT NULL,
    PID int NOT NULL,
    FOREIGN KEY (PID) REFERENCES Product (PID),
    FOREIGN KEY (CatName) REFERENCES Category (Name),
    PRIMARY KEY(PID, CatName)
);

CREATE TABLE Sold (
    StoreID int NOT NULL,
    PID int NOT NULL,
    Date date NOT NULL,
    Quantity int NOT NULL,
    FOREIGN KEY (PID) REFERENCES Product (PID),
    FOREIGN KEY (StoreID) REFERENCES Store (StoreID),
    FOREIGN KEY (Date) REFERENCES `Business Day` (Date),
    PRIMARY KEY(PID, StoreID, Date)
);

-- Insert Test (seed) Data 

-- Insert into User
-- example of using a 60 char length hashed password 'michael123' = $2y$08$kr5P80A7RyA0FDPUa8cB2eaf0EqbUay0nYspuajgHRRXM9SgzNgZO
-- depends on if you are storing the hash $storedHash or plaintext $storedPassword in processlogin.php
INSERT INTO `User` VALUES('admin@gtonline.com', 'admin123', 'StoreManager');
INSERT INTO `User` VALUES('dschrute@dundermifflin.com', 'dwight123', 'Marketing');
INSERT INTO `User` VALUES('gbluth@bluthco.com', 'george123', 'Coorporate');


-- Insert into City
INSERT INTO City VALUES('New Orleans', 'Louisana', 250000);
INSERT INTO City VALUES('New York City', 'New York', 7000000);
INSERT INTO City VALUES('Buffalo', 'New York',200000);


-- Insert into Store
INSERT INTO Store VALUES(1, '2038042325', '15 Hart Avenue', 1, 'New Orleans', 'Louisana');
INSERT INTO Store VALUES(2, '2038042325', '15 Hart Avenue', 0, 'New York City', 'New York');
INSERT INTO Store VALUES(3, '2038042325', '15 Hart Avenue', 1, 'New York City', 'New York');
INSERT INTO Store VALUES(4, '2038042325', '15 Hart Avenue', 1, 'Buffalo', 'New York');

-- Insert into Business Day
INSERT INTO `Business Day` VALUES('2021-02-02',0,0.0);
INSERT INTO `Business Day` VALUES('2020-02-02',1,0.25);
INSERT INTO `Business Day` VALUES('2021-02-03',1,0.75);
INSERT INTO `Business Day` VALUES('2020-02-03',0,0.0);

-- Insert into Manufacturer
INSERT INTO Manufacturer VALUES("Hasbro");
INSERT INTO Manufacturer VALUES("Lego");

-- Insert into Category
INSERT INTO Category VALUES("Toys");
INSERT INTO Category VALUES("Video Games");
INSERT INTO Category VALUES("Outdoor Furniture");
INSERT INTO Category VALUES("Couches and Sofas");


-- Insert into Product
INSERT INTO Product VALUES(1, 25.12, "Star Wars Set", "Lego");
INSERT INTO Product VALUES(2, 12.25, "Harry Potter Set", "Lego");
INSERT INTO Product VALUES(3, 40.12, "Transformers", "Hasbro");
INSERT INTO Product VALUES(4, 20.25, "Trasnformers Video Game", "Hasbro");
INSERT INTO Product VALUES(5, 500.25, "Harry Potter Set Mega", "Lego");
INSERT INTO Product VALUES(6, 100.25, "mini couch", "Hasbro");
INSERT INTO Product VALUES(7, 200.50, "Uber Couch", "Lego");
INSERT INTO Product VALUES(8, 55.50, "Little Chair", "Hasbro");
INSERT INTO Product VALUES(9, 65.50, "Bigger Chair", "Lego");

-- Insert into Manages
INSERT INTO Manages VALUES('admin@gtonline.com', 1);
INSERT INTO Manages VALUES('admin@gtonline.com', 2);

-- Insert into Discounted On
INSERT INTO `Discounted On` VALUES(1, "2021-02-02", 30.00);
INSERT INTO `Discounted On` VALUES(6, "2021-02-03", 30.00);
INSERT INTO `Discounted On` VALUES(7, "2021-02-03", 40.00);
INSERT INTO `Discounted On` VALUES(8, "2021-02-03", 45.00);
INSERT INTO `Discounted On` VALUES(9, "2021-02-03", 100.00);

-- Insert into IsInCategory 
INSERT INTO IsInCategory VALUES("Outdoor Furniture",1);
INSERT INTO IsInCategory VALUES("Outdoor Furniture",2);
INSERT INTO IsInCategory VALUES("Outdoor Furniture",3);
INSERT INTO IsInCategory VALUES("Outdoor Furniture",4);
INSERT INTO IsInCategory VALUES("Video Games",1);
INSERT INTO IsInCategory VALUES("Toys",4);
INSERT INTO IsInCategory VALUES("Couches and Sofas",6);
INSERT INTO IsInCategory VALUES("Couches and Sofas",7);
INSERT INTO IsInCategory VALUES("Couches and Sofas",8);
INSERT INTO IsInCategory VALUES("Couches and Sofas",9);


-- Insert into Sold
INSERT INTO Sold VALUES(1,1, "2021-02-02", 4);
INSERT INTO Sold VALUES(1,2, "2021-02-03", 3);
INSERT INTO Sold VALUES(1,2, "2020-02-03", 2);
INSERT INTO Sold VALUES(1,4, "2020-02-02", 2);
INSERT INTO Sold VALUES(2,1, "2021-02-02", 4);
INSERT INTO Sold VALUES(2,2, "2021-02-03", 3);
INSERT INTO Sold VALUES(2,2, "2020-02-03", 2);
INSERT INTO Sold VALUES(2,2, "2020-02-02", 2);
INSERT INTO Sold VALUES(3,2, "2021-02-03", 32);
INSERT INTO Sold VALUES(3,2, "2020-02-03", 15);
INSERT INTO Sold VALUES(3,4, "2020-02-02", 83);
INSERT INTO Sold VALUES(3,1, "2021-02-02", 30);
INSERT INTO Sold VALUES(4,1, "2021-02-02", 600);
INSERT INTO Sold VALUES(4,6, "2021-02-02", 600);
INSERT INTO Sold VALUES(4,7, "2021-02-02", 700);
INSERT INTO Sold VALUES(4,6, "2021-02-03", 800);
INSERT INTO Sold VALUES(4,7, "2021-02-03", 900);
INSERT INTO Sold VALUES(2,8, "2021-02-02", 100);
INSERT INTO Sold VALUES(2,9, "2021-02-02", 200);
INSERT INTO Sold VALUES(2,8, "2021-02-03", 300);
INSERT INTO Sold VALUES(2,9, "2021-02-03", 400);
