CREATE DATABASE clubedge;
\c clubedge;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE clubs (
    id SERIAL PRIMARY KEY,
    title VARCHAR(150) UNIQUE NOT NULL,
    description TEXT,
    logo VARCHAR(255),
    max_members INT NOT NULL DEFAULT 8 CHECK (max_members > 0)
);


CREATE TABLE club_memberships (
    id SERIAL PRIMARY KEY,
    club_id INT NOT NULL REFERENCES clubs(id) ON DELETE CASCADE,
    student_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    is_president BOOLEAN NOT NULL DEFAULT FALSE,
    UNIQUE (club_id, student_id)
);


CREATE TABLE events (
    id SERIAL PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    event_date TIMESTAMPTZ NOT NULL,
    location VARCHAR(150),
    images JSONB,
    status VARCHAR(30) NOT NULL DEFAULT 'active'
        CHECK (status IN ('active', 'cancelled', 'finished')),
    club_id INT NOT NULL REFERENCES clubs(id) ON DELETE CASCADE
);

CREATE TABLE participations (
    id SERIAL PRIMARY KEY,
    student_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    event_id INT NOT NULL REFERENCES events(id) ON DELETE CASCADE,
    has_participated BOOLEAN NOT NULL DEFAULT FALSE,
    UNIQUE (student_id, event_id)
);

CREATE TABLE reviews (
    id SERIAL PRIMARY KEY,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    student_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    event_id INT NOT NULL REFERENCES events(id) ON DELETE CASCADE,
    UNIQUE (student_id, event_id)
);


CREATE TABLE articles (
    id SERIAL PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    images JSONB,
    event_id INT NOT NULL REFERENCES events(id) ON DELETE CASCADE,
    club_id INT NOT NULL REFERENCES clubs(id) ON DELETE CASCADE,
    author_id INT REFERENCES users(id) ON DELETE SET NULL
);

