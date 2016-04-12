USE couse

INSERT INTO 
        TypeOfUser (typeName, typeId) 
    VALUES  
        ('Employee', 1),
        ('Leaders', 2),
        ('Admin', 3);

INSERT INTO 
        Users (name, login, pass, userTypeId) 
    VALUES 
        ('Володимир Кривенко', 'volody33', 'pass1', 1),
        ('Владислав Глухов', 'vladislav_gluh', 'pass2', 1),
        ('Тарас Табаков', 'taras', 'pass3', 1),
        ('Антон Красников', 'anton', 'pass4', 1),
        ('Руслан Стрижак', 'puslan_strijak', 'pass5', 2),
        ('Олександр Лієв', 'sasha777', 'pass6', 2),
        ('Адмін', 'admin', 'pass_admin', 3);

INSERT INTO 
        LeadersEmployee (employeeId, LeaderId) 
    VALUES 
        (1, 5),
        (2, 6);

INSERT INTO
        EmployeeSaraly (salary, employeeId, leaderId)
    VALUES
        (1000, 1, 5);