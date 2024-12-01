CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE vote_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT
);

CREATE TABLE votes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    voter_id INT NOT NULL,
    nominee_id INT NOT NULL,
    category_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (voter_id) REFERENCES employees(id),
    FOREIGN KEY (nominee_id) REFERENCES employees(id),
    FOREIGN KEY (category_id) REFERENCES vote_categories(id)
);

INSERT INTO employees (name, email, password) VALUES
('John Doe', 'john.doe@company.com', '$2y$10$somehashedpassword1'),
('Jane Smith', 'jane.smith@company.com', '$2y$10$somehashedpassword2'),
('Mike Johnson', 'mike.johnson@company.com', '$2y$10$somehashedpassword3'),
('Sarah Williams', 'sarah.williams@company.com', '$2y$10$somehashedpassword4'),
('Robert Brown', 'robert.brown@company.com', '$2y$10$somehashedpassword5');

INSERT INTO vote_categories (name, description) VALUES
('Makes Work Fun', 'Recognizes employees who contribute to a positive and enjoyable work environment'),
('Team Player', 'Acknowledges those who collaborate effectively with their colleagues'),
('Difference Maker', 'Recognizes employees who consistently deliver exceptional results and drive positive change'),
('Culture Champion', 'Acknowledges those who actively promote and embody the company\'s values and culture');