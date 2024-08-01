-- Creating the database
CREATE DATABASE evangelion CHARACTER SET utf8 COLLATE utf8_general_ci;

USE evangelion;

-- Create Product table
CREATE TABLE Product (
    pro_id INT AUTO_INCREMENT PRIMARY KEY,
    pro_name VARCHAR(255) NOT NULL,
-- store the path of image instead of storing image directly which consume large space & memory
    img1 VARCHAR(255), 
    img2 VARCHAR(255),
    img3 VARCHAR(255),
    price DECIMAL(10, 2),
    discount_p DECIMAL(10, 2),
    brand VARCHAR(255),
    category VARCHAR(255)
);

-- Create Member table
CREATE TABLE Member (
    mem_id INT AUTO_INCREMENT PRIMARY KEY,
    mem_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    contact VARCHAR(20) NOT NULL,
    mem_pass VARCHAR(20) NOT NULL,
    gender ENUM('Male', 'Female', 'Other'),
    UNIQUE KEY email (email)
);

-- Create Sales table
CREATE TABLE Sales (
    sales_id INT AUTO_INCREMENT PRIMARY KEY,
    mem_id INT,
    total_price DECIMAL(10, 2),
    sales_date DATE,
    FOREIGN KEY (mem_id) REFERENCES Member(mem_id)
);

-- Create Sales_Record table
CREATE TABLE Sales_Record (
    sales_id INT,
    mem_id INT,
    product_id INT,
    PRIMARY KEY (sales_id, product_id),
    FOREIGN KEY (mem_id) REFERENCES Member(mem_id),
    FOREIGN KEY (sales_id) REFERENCES Sales(sales_id),
    FOREIGN KEY (product_id) REFERENCES Product(pro_id)
);

-- Create Favorite table
CREATE TABLE Favorite (
    mem_id INT,
    product_id INT,
    PRIMARY KEY (mem_id, product_id),
    FOREIGN KEY (mem_id) REFERENCES Member(mem_id),
    FOREIGN KEY (product_id) REFERENCES Product(pro_id)
);

