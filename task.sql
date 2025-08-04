CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    group_name VARCHAR(100),
    member_name VARCHAR(100),
    priority VARCHAR(20),
    deadline DATE,
    status VARCHAR(50)
);