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