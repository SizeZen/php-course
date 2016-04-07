USE couse

INSERT INTO TypeOfUser (typeName, typeId) 
    VALUES  
        ('Employee', 1),
        ('Leaders', 2),
        ('Admin', 3);

INSERT INTO 
        Users (name, login, pass, userTypeId) 
    VALUES 
        ('Petro', 'prt33', 'asdfasdf1', 1),
        ('Slava', 'slvp32', 'asdfasdf2', 1),
        ('Vasya', 'vsl43', 'asdfasdf3', 2),
        ('Vlad', 'vld53', 'asdfasdf4', 3);

INSERT INTO 
        LeadersEmployee (employeeId, LeaderId) 
    VALUES 
        (1, 3),
        (2, 3);