-- Drop the existing root user for any host (if exists)
DROP USER IF EXISTS 'root'@'%';

-- Recreate the root user to allow connections from any host with no password
CREATE USER 'root'@'%' IDENTIFIED BY '';

-- Grant all privileges to the root user
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;

-- Apply the changes
FLUSH PRIVILEGES;
