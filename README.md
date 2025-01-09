## Installation
1. Clone or download the repository.
2. Import the database schema to MySQL:
   ```sql
   CREATE TABLE `books` (
       `id` INT AUTO_INCREMENT PRIMARY KEY,
       `title` VARCHAR(255) NOT NULL,
       `author` VARCHAR(255) NOT NULL,
       `genre` VARCHAR(255) NOT NULL,
       `release_date` DATE NOT NULL,
       `available` BOOLEAN DEFAULT true,
       `quantity` INT NOT NULL
   );
