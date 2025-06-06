-- Membuat tabel user untuk login
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL
);

-- Membuat tabel kamera
CREATE TABLE IF NOT EXISTS cameras (
    id SERIAL PRIMARY KEY,
    camera_id INTEGER UNIQUE NOT NULL,
    room_name TEXT NOT NULL
);

-- Membuat tabel hasil deteksi
CREATE TABLE IF NOT EXISTS detections (
    id SERIAL PRIMARY KEY,
    camera_id INTEGER NOT NULL,
    room_name TEXT,
    timestamp TIMESTAMP,
    person_count INTEGER,
    image_path TEXT,
    FOREIGN KEY (camera_id) REFERENCES cameras(camera_id) ON DELETE CASCADE
);
