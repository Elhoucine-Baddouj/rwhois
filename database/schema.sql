-- Minimal schema for RWHOIS Dashboard (only users, organizations, servers)

CREATE DATABASE IF NOT EXISTS rwhois CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rwhois;

-- Table: organizations
CREATE TABLE IF NOT EXISTS organizations (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('ISP','Hosting Provider','Cloud Provider','Enterprise','Other') DEFAULT 'Other',
    contact_email VARCHAR(100) DEFAULT NULL,
    contact_phone VARCHAR(20) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    description TEXT DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin','manager','observer') NOT NULL DEFAULT 'observer',
    organization_id INT DEFAULT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    password VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organization_id) REFERENCES organizations(id)
);

-- Table: servers
CREATE TABLE IF NOT EXISTS servers (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    server_ip VARCHAR(45) NOT NULL,
    instance_id INT NOT NULL,
    port INT NOT NULL DEFAULT 4321,
    organization_id INT DEFAULT NULL,
    description TEXT DEFAULT NULL,
    location VARCHAR(100) DEFAULT NULL,
    environment ENUM('production','staging','development','testing','backup') DEFAULT 'production',
    status ENUM('active','inactive','maintenance','error','installing','uninstalling') DEFAULT 'inactive',
    install_path VARCHAR(255) DEFAULT NULL,
    config_path VARCHAR(255) DEFAULT NULL,
    log_path VARCHAR(255) DEFAULT NULL,
    service_name VARCHAR(100) DEFAULT NULL,
    ssh_user VARCHAR(50) DEFAULT 'root',
    ssh_key_path VARCHAR(255) DEFAULT NULL,
    last_status_check DATETIME DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organization_id) REFERENCES organizations(id)
);

-- Table: network_resources
CREATE TABLE IF NOT EXISTS network_resources (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    organization_id INT NOT NULL,
    type ENUM('ASN','IPv4','IPv6') NOT NULL,
    value VARCHAR(64) NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organization_id) REFERENCES organizations(id)
); 