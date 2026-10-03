-- Travelora demo seed data (destinations, hotels, flights, packages, activities).
-- Realistic sample content for a fresh install.

-- Destinations (first 5 are featured on homepage, like the design)
INSERT INTO destinations (name, country, slug, image, tagline, description, price_from, rating, featured) VALUES
('Kuala Lumpur', 'Malaysia', 'kuala-lumpur', 'assets/img/destinations/kuala-lumpur.jpg', 'Where heritage meets the skyline', 'Malaysia''s vibrant capital blends soaring skyscrapers with rich culture, street food and colonial charm.', 120.00, 4.8, 1),
('Langkawi', 'Malaysia', 'langkawi', 'assets/img/destinations/langkawi.jpg', 'Island escape in the Andaman Sea', 'A tropical archipelago of rainforests, mangroves, white-sand beaches and legendary island hopping.', 150.00, 4.7, 1),
('Bali', 'Indonesia', 'bali', 'assets/img/destinations/bali.jpg', 'Island of the gods', 'Temples, rice terraces, surf beaches and wellness retreats make Bali a traveller''s paradise.', 180.00, 4.9, 1),
('Tokyo', 'Japan', 'tokyo', 'assets/img/destinations/tokyo.jpg', 'Neon lights, timeless tradition', 'From serene shrines to buzzing districts, Tokyo is a feast of food, technology and culture.', 320.00, 4.8, 1),
('Seoul', 'South Korea', 'seoul', 'assets/img/destinations/seoul.jpg', 'Where K-culture meets ancient palaces', 'Palaces, street markets, K-pop and mountain trails - Seoul is endlessly exciting.', 280.00, 4.7, 1),
('Bangkok', 'Thailand', 'bangkok', 'assets/img/destinations/bangkok.jpg', 'The city of angels', 'Temples, floating markets and legendary street food in Thailand''s energetic capital.', 110.00, 4.6, 0),
('Singapore', 'Singapore', 'singapore', 'assets/img/destinations/singapore.jpg', 'A garden city of endless flavour', 'Futuristic gardens, hawker food and world-class attractions in one compact island city.', 95.00, 4.8, 0),
('Phuket', 'Thailand', 'phuket', 'assets/img/destinations/phuket.jpg', 'Pearl of the Andaman', 'Thailand''s largest island offers beaches, nightlife and easy access to Phi Phi and Phang Nga Bay.', 140.00, 4.7, 0),
('Maldives', 'Maldives', 'maldives', 'assets/img/destinations/maldives.jpg', 'Overwater bliss', 'Private villas, coral reefs and impossibly blue water - the ultimate honeymoon escape.', 450.00, 4.9, 0),
('Dubai', 'United Arab Emirates', 'dubai', 'assets/img/destinations/dubai.jpg', 'Where the desert meets the sky', 'Record-breaking skyscrapers, desert safaris and gold souks in the Gulf''s glamour capital.', 300.00, 4.7, 0),
('Paris', 'France', 'paris', 'assets/img/destinations/paris.jpg', 'The city of light', 'Art, fashion, cafés and the Eiffel Tower - Paris never goes out of style.', 350.00, 4.8, 0),
('Hanoi', 'Vietnam', 'hanoi', 'assets/img/destinations/hanoi.jpg', 'Thousand-year-old charm', 'Old Quarter alleys, lakeside temples and the best pho of your life.', 90.00, 4.6, 0),
('Hong Kong', 'China', 'hong-kong', 'assets/img/destinations/hong-kong.jpg', 'Asia''s world city', 'Harbour skylines, dim sum, night markets and mountain hikes minutes from downtown.', 160.00, 4.7, 0);

-- Hotels
INSERT INTO hotels (destination_id, name, image, stars, rating, reviews, address, price_per_night, amenities) VALUES
(1, 'Skyline Grand Kuala Lumpur', 'assets/img/hotels/hotel-1.jpg', 5, 4.8, 2140, 'Jalan Bukit Bintang, Kuala Lumpur', 145.00, 'Pool, Spa, Free WiFi, Gym, Breakfast'),
(1, 'Petronas View Boutique Hotel', 'assets/img/hotels/hotel-2.jpg', 4, 4.6, 1320, 'KLCC, Kuala Lumpur', 98.00, 'Free WiFi, Rooftop Bar, Breakfast'),
(2, 'Langkawi Rainforest Resort', 'assets/img/hotels/hotel-3.jpg', 5, 4.9, 1875, 'Pantai Cenang, Langkawi', 210.00, 'Beachfront, Pool, Spa, Kayaks, Breakfast'),
(2, 'Cenang Bay Inn', 'assets/img/hotels/hotel-4.jpg', 4, 4.5, 940, 'Pantai Cenang, Langkawi', 85.00, 'Pool, Free WiFi, Airport Shuttle'),
(3, 'Ubud Jungle Villa & Spa', 'assets/img/hotels/hotel-5.jpg', 5, 4.9, 3010, 'Ubud, Bali', 175.00, 'Infinity Pool, Spa, Yoga, Breakfast'),
(3, 'Seminyak Beach Hotel', 'assets/img/hotels/hotel-6.jpg', 4, 4.6, 2210, 'Seminyak, Bali', 120.00, 'Beach Access, Pool, Free WiFi, Bar'),
(4, 'Shinjuku Tower Hotel', 'assets/img/hotels/hotel-1.jpg', 5, 4.8, 4120, 'Shinjuku, Tokyo', 260.00, 'City View, Spa, Free WiFi, Restaurant'),
(4, 'Asakusa Ryokan Garden', 'assets/img/hotels/hotel-2.jpg', 4, 4.7, 1650, 'Asakusa, Tokyo', 180.00, 'Onsen, Garden, Breakfast, Free WiFi'),
(5, 'Gangnam Luxury Suites', 'assets/img/hotels/hotel-3.jpg', 5, 4.7, 2380, 'Gangnam, Seoul', 195.00, 'Pool, Gym, Spa, Free WiFi'),
(5, 'Myeongdong Central Hotel', 'assets/img/hotels/hotel-4.jpg', 4, 4.5, 1910, 'Myeongdong, Seoul', 130.00, 'Free WiFi, Breakfast, Concierge'),
(6, 'Chao Phraya Riverside', 'assets/img/hotels/hotel-5.jpg', 5, 4.8, 2760, 'Riverside, Bangkok', 150.00, 'River View, Pool, Spa, Free WiFi'),
(7, 'Marina Bay Sky Hotel', 'assets/img/hotels/hotel-6.jpg', 5, 4.9, 3240, 'Marina Bay, Singapore', 285.00, 'Infinity Pool, Sky Bar, Gym, Free WiFi'),
(8, 'Patong Beach Resort', 'assets/img/hotels/hotel-2.jpg', 4, 4.6, 2050, 'Patong, Phuket', 125.00, 'Beachfront, Pool, Free WiFi, Breakfast'),
(9, 'Coral Overwater Villas', 'assets/img/hotels/hotel-3.jpg', 5, 5.0, 1180, 'North Male Atoll, Maldives', 680.00, 'Overwater Villa, Snorkeling, Spa, All-Inclusive'),
(10, 'Downtown Dubai Palace', 'assets/img/hotels/hotel-4.jpg', 5, 4.8, 3560, 'Downtown, Dubai', 320.00, 'Burj View, Pool, Spa, Free WiFi'),
(11, 'Le Marais Boutique Hotel', 'assets/img/hotels/hotel-5.jpg', 4, 4.7, 1980, 'Le Marais, Paris', 240.00, 'Breakfast, Free WiFi, Concierge'),
(12, 'Hoan Kiem Lake Hotel', 'assets/img/hotels/hotel-6.jpg', 4, 4.6, 1420, 'Old Quarter, Hanoi', 75.00, 'Lake View, Free WiFi, Breakfast'),
(13, 'Victoria Harbour Hotel', 'assets/img/hotels/hotel-1.jpg', 5, 4.8, 2890, 'Tsim Sha Tsui, Hong Kong', 230.00, 'Harbour View, Pool, Gym, Free WiFi');

-- Flights
INSERT INTO flights (airline, from_city, from_code, to_city, to_code, depart_time, arrive_time, duration, stops, price, seats_left) VALUES
('Malaysia Airlines', 'Kuala Lumpur', 'KUL', 'Tokyo', 'NRT', '23:35:00', '07:30:00', '6h 55m', 0, 289.00, 12),
('AirAsia X', 'Kuala Lumpur', 'KUL', 'Tokyo', 'NRT', '14:20:00', '22:35:00', '7h 15m', 0, 199.00, 24),
('Malaysia Airlines', 'Kuala Lumpur', 'KUL', 'Denpasar', 'DPS', '09:10:00', '12:15:00', '3h 05m', 0, 135.00, 18),
('Batik Air', 'Kuala Lumpur', 'KUL', 'Denpasar', 'DPS', '16:45:00', '19:50:00', '3h 05m', 0, 110.00, 9),
('Korean Air', 'Kuala Lumpur', 'KUL', 'Seoul', 'ICN', '22:50:00', '06:25:00', '6h 35m', 0, 265.00, 14),
('AirAsia X', 'Kuala Lumpur', 'KUL', 'Seoul', 'ICN', '08:15:00', '15:55:00', '6h 40m', 0, 215.00, 21),
('Thai Airways', 'Kuala Lumpur', 'KUL', 'Bangkok', 'BKK', '10:30:00', '11:45:00', '2h 15m', 0, 89.00, 30),
('Singapore Airlines', 'Kuala Lumpur', 'KUL', 'Singapore', 'SIN', '07:40:00', '08:45:00', '1h 05m', 0, 65.00, 27),
('Cathay Pacific', 'Kuala Lumpur', 'KUL', 'Hong Kong', 'HKG', '13:05:00', '17:10:00', '4h 05m', 0, 175.00, 16),
('Emirates', 'Kuala Lumpur', 'KUL', 'Dubai', 'DXB', '20:30:00', '23:45:00', '7h 15m', 0, 310.00, 22),
('Qatar Airways', 'Kuala Lumpur', 'KUL', 'Paris', 'CDG', '01:55:00', '13:20:00', '17h 25m', 1, 520.00, 8),
('AirAsia', 'Kuala Lumpur', 'KUL', 'Hanoi', 'HAN', '11:20:00', '13:50:00', '3h 30m', 0, 105.00, 19),
('Malindo Air', 'Kuala Lumpur', 'KUL', 'Phuket', 'HKT', '15:10:00', '15:55:00', '1h 45m', 0, 72.00, 23),
('Batik Air', 'Kuala Lumpur', 'KUL', 'Male', 'MLE', '09:45:00', '11:20:00', '3h 35m', 0, 240.00, 11);

-- Packages
INSERT INTO packages (destination_id, title, image, days, nights, price, old_price, includes, rating, badge) VALUES
(2, 'Langkawi Island Hopper Escape', 'assets/img/packages/package-1.jpg', 4, 3, 399.00, 520.00, 'Hotel 4*, Island hopping tour, Airport transfers, Daily breakfast', 4.8, 'Best Seller'),
(3, 'Bali Temples & Rice Terraces', 'assets/img/packages/package-2.jpg', 6, 5, 649.00, 810.00, 'Villa 4*, Ubud tour, Uluwatu sunset, Daily breakfast, Private driver', 4.9, 'Popular'),
(4, 'Tokyo Neon & Cherry Blossom', 'assets/img/packages/package-3.jpg', 7, 6, 1199.00, 1450.00, 'Hotel 4*, JR Pass 7-day, Mt Fuji day trip, City tour, Breakfast', 4.8, 'Trending'),
(5, 'Seoul K-Culture Discovery', 'assets/img/packages/package-4.jpg', 5, 4, 899.00, 1099.00, 'Hotel 4*, Palace & Hanbok tour, Nami Island, K-BBQ dinner', 4.7, NULL),
(1, 'Kuala Lumpur City Break', 'assets/img/packages/package-1.jpg', 3, 2, 249.00, 320.00, 'Hotel 5*, KL Tower tickets, City tour, Airport transfer', 4.7, 'Deal'),
(9, 'Maldives Overwater Dream', 'assets/img/packages/package-2.jpg', 5, 4, 1799.00, 2100.00, 'Overwater villa, All-inclusive meals, Seaplane transfers, Snorkeling', 5.0, 'Luxury'),
(6, 'Bangkok Temples & Markets', 'assets/img/packages/package-3.jpg', 4, 3, 329.00, 420.00, 'Hotel 4*, Grand Palace tour, Floating market, Street food tour', 4.6, NULL),
(7, 'Singapore City of Gardens', 'assets/img/packages/package-4.jpg', 4, 3, 459.00, 560.00, 'Hotel 4*, Gardens by the Bay, Sentosa pass, Universal Studios ticket', 4.8, 'Popular');

-- Things to do
INSERT INTO things_to_do (destination_id, title, image, category, duration, price, rating, reviews) VALUES
(2, 'Langkawi Island Hopping & Snorkeling', 'assets/img/activities/activity-1.jpg', 'Water Sports', '6 hours', 45.00, 4.8, 1240),
(2, 'Sky Bridge & Cable Car Experience', 'assets/img/activities/activity-2.jpg', 'Sightseeing', '3 hours', 28.00, 4.7, 980),
(3, 'Ubud Monkey Forest & Rice Terrace Tour', 'assets/img/activities/activity-3.jpg', 'Nature', '8 hours', 55.00, 4.9, 2310),
(3, 'Mount Batur Sunrise Trek', 'assets/img/activities/activity-1.jpg', 'Adventure', '10 hours', 42.00, 4.8, 1875),
(4, 'Mt Fuji & Hakone Day Trip', 'assets/img/activities/activity-2.jpg', 'Day Trip', '11 hours', 89.00, 4.7, 3120),
(4, 'Tokyo Street Food Walking Tour', 'assets/img/activities/activity-4.jpg', 'Food & Drink', '3 hours', 65.00, 4.9, 1540),
(5, 'Gyeongbokgung Palace & Hanbok Rental', 'assets/img/activities/activity-3.jpg', 'Culture', '4 hours', 35.00, 4.8, 2050),
(5, 'N Seoul Tower & Namsan Hike', 'assets/img/activities/activity-2.jpg', 'Sightseeing', '3 hours', 25.00, 4.6, 1320),
(1, 'Petronas Towers Skybridge & Observation', 'assets/img/activities/activity-4.jpg', 'Sightseeing', '2 hours', 30.00, 4.8, 4100),
(1, 'Batu Caves & City Highlights Tour', 'assets/img/activities/activity-1.jpg', 'Culture', '4 hours', 38.00, 4.7, 2680),
(6, 'Grand Palace & Emerald Buddha Tour', 'assets/img/activities/activity-3.jpg', 'Culture', '4 hours', 40.00, 4.7, 3200),
(8, 'Phi Phi Islands Speedboat Day Trip', 'assets/img/activities/activity-1.jpg', 'Water Sports', '9 hours', 75.00, 4.8, 2950);