-- Initial database setup for Laravel
-- This file is executed when MySQL container starts for the first time
-- Set default charset
SET
    NAMES utf8mb4;

SET
    FOREIGN_KEY_CHECKS = 0;

-- Create database if not exists (already created via environment variables)
-- but we can add any additional setup here
-- Example: Create additional databases for testing
-- CREATE DATABASE IF NOT EXISTS `laravel_testing`;
-- Set foreign key checks back
SET
    FOREIGN_KEY_CHECKS = 1;