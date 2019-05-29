Drop database if exists Vignettes;
Create database Vignettes CHARSET 'utf8';
use Vignettes;

create table Countries(
	CountryId INT primary key auto_increment,
    Name VARCHAR(50) not null
);

create table Categories(
	CategoryId INT primary key auto_increment,
    Name varchar(10) not null
);

Create table Vignettes_data(
	Id INT primary key auto_increment,
    Regnum VARCHAR(8) not null,
    CategoryId INT not null,
    CountryId INT not null,
    Valid_from datetime not null,
    Valid_to datetime not null,
    
    FOREIGN key (CountryId) references Countries(CountryId) on update cascade,
    foreign key (CategoryId) references Categories(CategoryId) on update cascade
);







