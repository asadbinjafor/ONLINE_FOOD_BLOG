-- Optional sample content. Run once after database.pgsql.sql.
INSERT INTO restaurants (name, location, area, short_background, goals) VALUES
('Spice Garden', 'Dhaka', 'Gulshan', 'Authentic Bangladeshi cuisine with family recipes since 1998.', 'Celebrate local flavors and seasonal ingredients.'),
('Pasta Bella', 'Dhaka', 'Banani', 'Italian trattoria serving handmade pasta and wood-fired pizzas.', 'Bring classic Italian comfort food to the city.'),
('Sushi Harbor', 'Chittagong', 'Agrabad', 'Fresh seafood and traditional Japanese techniques.', 'Promote sustainable fishing and omakase experiences.');

INSERT INTO menu_items (restaurant_id, name, description, price) VALUES
((SELECT id FROM restaurants WHERE name = 'Spice Garden' ORDER BY id LIMIT 1), 'Kacchi Biryani', 'Slow-cooked mutton biryani with potatoes and aromatic spices.', 450.00),
((SELECT id FROM restaurants WHERE name = 'Spice Garden' ORDER BY id LIMIT 1), 'Beef Tehari', 'Fragrant rice with tender beef and caramelized onions.', 380.00),
((SELECT id FROM restaurants WHERE name = 'Pasta Bella' ORDER BY id LIMIT 1), 'Truffle Pasta', 'Creamy fettuccine with truffle oil and parmesan.', 650.00),
((SELECT id FROM restaurants WHERE name = 'Pasta Bella' ORDER BY id LIMIT 1), 'Margherita Pizza', 'Stone-baked pizza with fresh mozzarella and basil.', 520.00),
((SELECT id FROM restaurants WHERE name = 'Sushi Harbor' ORDER BY id LIMIT 1), 'Salmon Nigiri Set', 'Six pieces of fresh salmon nigiri with wasabi.', 890.00),
((SELECT id FROM restaurants WHERE name = 'Sushi Harbor' ORDER BY id LIMIT 1), 'Dragon Roll', 'Tempura shrimp roll topped with avocado and eel sauce.', 720.00);
