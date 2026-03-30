-- SAÉ 203 Movie Catalog Database Initialization

CREATE DATABASE IF NOT EXISTS sae203;
USE sae203;

-- Table for genres
CREATE TABLE IF NOT EXISTS genres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for movies
CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    annee INT NOT NULL,
    duree INT NOT NULL, -- in minutes
    genre_id INT NOT NULL,
    realisateur VARCHAR(255) NOT NULL,
    acteurs TEXT NOT NULL,
    resume TEXT NOT NULL,
    affiche_url VARCHAR(255) NOT NULL,
    bande_annonce_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for comments
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    pseudo VARCHAR(50) NOT NULL,
    commentaire TEXT NOT NULL,
    note INT NOT NULL CHECK (note BETWEEN 1 AND 5),
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default genres
INSERT INTO genres (nom) VALUES 
('Action'), 
('Aventure'), 
('Comédie'), 
('Drame'), 
('Horreur'), 
('S-F'), 
('Thriller'), 
('Animation'), 
('Documentaire');

-- Sample movies
INSERT INTO movies (titre, annee, duree, genre_id, realisateur, acteurs, resume, affiche_url, bande_annonce_url) VALUES 
('Inception', 2010, 148, 6, 'Christopher Nolan', 'Leonardo DiCaprio, Joseph Gordon-Levitt', 'Un voleur qui subtilise des secrets d''entreprise à travers l''utilisation de la technologie de partage de rêves.', 'https://image.tmdb.org/t/p/w500/9gk7Fn9sVAsS9Te6B3MCOQ6YybM.jpg', 'https://www.youtube.com/embed/YoHD9XEInc0'),
('The Dark Knight', 2008, 152, 1, 'Christopher Nolan', 'Christian Bale, Heath Ledger', 'Batman doit accepter l''un des plus grands défis psychologiques et physiques de son combat contre l''injustice.', 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDp9QmSJJilpFiIvGqt.jpg', 'https://www.youtube.com/embed/EXeTwQWrcwY'),
('Interstellar', 2014, 169, 6, 'Christopher Nolan', 'Matthew McConaughey, Anne Hathaway', 'Une équipe d''explorateurs voyage à travers un trou de ver dans l''espace pour tenter d''assurer la survie de l''humanité.', 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6vCU67oYvBPXT.jpg', 'https://www.youtube.com/embed/zSWdZVtXT7E'),
('Pulp Fiction', 1994, 154, 4, 'Quentin Tarantino', 'John Travolta, Samuel L. Jackson', 'Les vies de deux tueurs à gages, d''un boxeur, d''une femme de gangster et de deux bandits de bas étage s''entremêlent.', 'https://image.tmdb.org/t/p/w500/d5iIl9h9FvS6P9IqThBN06IqzYv.jpg', 'https://www.youtube.com/embed/s7EdQ4FqbhY'),
('Parasite', 2019, 132, 7, 'Bong Joon-ho', 'Song Kang-ho, Lee Sun-kyun', 'Toute la famille de Ki-taek est au chômage et s''intéresse particulièrement au train de vie de la richissime famille Park.', 'https://image.tmdb.org/t/p/w500/7S96-W777pLcc8O999m6B6pXvX0.jpg', 'https://www.youtube.com/embed/5xH0HfJHsaY'),
('The Matrix', 1999, 136, 6, 'Lana Wachowski', 'Keanu Reeves, Laurence Fishburne', 'Un programmeur informatique découvre que la réalité telle qu''il la connaît est une simulation créée par des machines.', 'https://image.tmdb.org/t/p/w500/f89U3Y9L9u2MqwT7EnZp7S1Imsr.jpg', 'https://www.youtube.com/embed/m8e-FF8MsqU'),
('Gladiator', 2000, 155, 1, 'Ridley Scott', 'Russell Crowe, Joaquin Phoenix', 'Un général romain déchu cherche à se venger de l''empereur corrompu qui a assassiné sa famille et l''a envoyé en esclavage.', 'https://image.tmdb.org/t/p/w500/ty8TGRvS21QvJs6Mclv-pZisXAz.jpg', 'https://www.youtube.com/embed/owK1S_2fshY'),
('Spirited Away', 2001, 125, 8, 'Hayao Miyazaki', 'Rumi Hiiragi, Miyu Irino', 'Une jeune fille s''égare dans un monde régi par des dieux, des sorcières et des esprits, et où les humains sont transformés en bêtes.', 'https://image.tmdb.org/t/p/w500/39wmItSslvJWjpvA7HSzpUIrA9m.jpg', 'https://www.youtube.com/embed/ByXuk9QqQkk'),
('The Godfather', 1972, 175, 4, 'Francis Ford Coppola', 'Marlon Brando, Al Pacino', 'L''histoire de la famille Corleone sous la direction de leur patriarche vieillissant alors qu''ils étendent leur influence.', 'https://image.tmdb.org/t/p/w500/3bhkrjSTWv4MAn6aXAc9L9P9A9C.jpg', 'https://www.youtube.com/embed/sY1S34973zA'),
('Joker', 2019, 122, 4, 'Todd Phillips', 'Joaquin Phoenix, Robert De Niro', 'Un comédien de stand-up raté, méprisé par la société, bascule dans la folie et devient le génie du crime connu sous le nom de Joker.', 'https://image.tmdb.org/t/p/w500/udDclsvMblWmo0R2bWACsi9pGQj.jpg', 'https://www.youtube.com/embed/zAGVQLHvwOY');

-- Sample comments
INSERT INTO comments (movie_id, pseudo, note, commentaire) VALUES 
(1, 'Cinéphile99', 5, 'Un chef-d''œuvre de Nolan. Le concept est fascinant.'),
(1, 'Dreamer', 4, 'Un peu complexe au début mais la fin est mémorable.'),
(2, 'BatmanFan', 5, 'Le meilleur film de super-héros de tous les temps.'),
(3, 'StarGazer', 5, 'La musique de Hans Zimmer et les visuels sont incroyables.'),
(4, 'TarantinoLover', 5, 'Des dialogues cultes et une mise en scène unique.'),
(5, 'MovieBuff', 5, 'Une critique sociale brillante et pleine de suspense.'),
(10, 'ArthurFleck', 4, 'Une performance incroyable de Joaquin Phoenix.');
