/* user stuff */
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NULL,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  bio TEXT NULL,
  pronouns VARCHAR(50) NULL,
  interests TEXT NULL,
  phone VARCHAR(25) NULL,
  date_of_birth DATE NULL,
  profile_picture VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/* password reset */
CREATE TABLE IF NOT EXISTS password_resets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token_hash VARCHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  used_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

/* attractions stuff */
CREATE TABLE IF NOT EXISTS attractions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  description TEXT,
  location VARCHAR(120),
  image_url VARCHAR(255)
);

-- form submission php
CREATE TABLE IF NOT EXISTS submissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  business_name VARCHAR(120) NOT NULL,
  attraction_name VARCHAR(120) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  budget VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS favorites (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  attraction_id INT NOT NULL,
  type ENUM('favorite', 'saved') NOT NULL DEFAULT 'favorite',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_user_attraction (user_id, attraction_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (attraction_id) REFERENCES attractions(id) ON DELETE CASCADE
);
INSERT INTO attractions (name, description, location, image_url) VALUES
  ('Baby Joeys Alligator Farm', 'Enjoy good, old-fashioned family fun at a family-owned, all-natural alligator sanctuary! Enjoy live gators, train rides, an assortment of food and drink, educational shows, and more!', '1234 Baby Joey Avenue, Sanford, FL 36767', '/images/alligator.jpg'),
  ('Orange Grove Mini Golf', 'A citrus-themed mini golf course tucked inside sunny orange groves. Fun, simple, and perfect for families or anyone wanting a sweet little adventure.', 'Orlando, FL', '/images/golf.jpg'),
  ('Skyline Rooftop Lounge', 'An upscale rooftop escape offering breathtaking panoramic views of the city skyline. Enjoy handcrafted cocktails, ambient lighting, and live music under the stars.', '100 Skyline Avenue, Orlando, FL 32801', '/images/rooftop.jpg'),
  ('Winter Town Botanical Trails', 'A peaceful botanical garden filled with seasonal blooms and cozy winter charm. Perfect for slow walks, quiet moments, and nature lovers of all ages.', 'Orlando, FL', '/images/botanical.jpg');


