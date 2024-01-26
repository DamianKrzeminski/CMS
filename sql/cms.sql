-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 26 Sty 2024, 18:19
-- Wersja serwera: 10.1.35-MariaDB
-- Wersja PHP: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `cms`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(3) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cat_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `categories`
--

INSERT INTO `categories` (`cat_id`, `user_id`, `cat_title`) VALUES
(1, 16, 'Bootstrap'),
(2, 17, 'Javascript'),
(3, 16, 'PHP'),
(4, 17, 'JAVA'),
(13, 16, 'HTML5'),
(16, 16, 'Phyton');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(3) NOT NULL,
  `comment_post_id` int(3) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_author` varchar(255) NOT NULL,
  `comment_email` varchar(255) NOT NULL,
  `comment_content` text NOT NULL,
  `comment_status` varchar(255) NOT NULL,
  `comment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `comments`
--

INSERT INTO `comments` (`comment_id`, `comment_post_id`, `user_id`, `comment_author`, `comment_email`, `comment_content`, `comment_status`, `comment_date`) VALUES
(38, 81, 16, 'QuÃ¡zarV13', 'damian.krzeminski13@gmail.com', '123', 'approved', '2018-10-05'),
(39, 81, 16, 'QuÃ¡zarS', 'damian.krzeminski13@gmail.com', '1234', 'unapproved', '2018-10-05'),
(40, 81, 16, 'QuÃ¡zar', 'damian.krzeminski13@gmail.com', '12345', 'unapproved', '2018-10-05'),
(52, 81, 16, 'QuÃ¡zar', 'damian.krzeminski13@gmail.com', '1234567890', 'approved', '2018-10-05'),
(53, 81, 16, 'QuÃ¡zar', 'damian.krzeminski13@gmail.com', '123test', 'approved', '2018-10-05');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`) VALUES
(3, 18, 73),
(4, 18, 73),
(5, 17, 119);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `posts`
--

CREATE TABLE `posts` (
  `post_id` int(3) NOT NULL,
  `post_category_id` int(3) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_author` varchar(255) NOT NULL,
  `post_user` varchar(255) NOT NULL,
  `post_date` date NOT NULL,
  `post_image` text NOT NULL,
  `post_content` text NOT NULL,
  `post_tags` varchar(255) NOT NULL,
  `post_comment_count` int(11) NOT NULL,
  `post_status` varchar(255) NOT NULL DEFAULT 'draft',
  `post_views_count` int(11) NOT NULL,
  `likes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `posts`
--

INSERT INTO `posts` (`post_id`, `post_category_id`, `user_id`, `post_title`, `post_author`, `post_user`, `post_date`, `post_image`, `post_content`, `post_tags`, `post_comment_count`, `post_status`, `post_views_count`, `likes`) VALUES
(81, 2, 16, 'Javascript', '', 'QuÃ¡zar', '2018-10-05', 'csm_project_jv.jpg', '<p>123</p>', 'Javascript, QuÃ¡zar', 0, 'published', 72, 0),
(82, 3, 16, 'PHP', '', 'QuÃ¡zar', '2018-10-05', 'csm_project_php.jpg', '<p>123</p>', 'PHP, QuÃ¡zar', 0, 'published', 0, 0),
(83, 1, 16, 'Bootstrap', '', 'QuÃ¡zar', '2018-10-05', 'csm_project_jv.jpg', '<p>123</p>', 'Bootstrap, QuÃ¡zarV13', 0, 'published', 11, 0),
(84, 13, 16, 'HTML5', '', 'QuÃ¡zar', '2018-10-05', 'csm_project_php.jpg', '<p>123</p>', 'HTML5, QuÃ¡zarV13', 0, 'published', 1, 0),
(85, 4, 16, 'Java', '', 'QuÃ¡zar', '2018-10-05', 'csm_project_jv.jpg', '<p>123</p>', 'Java, QuÃ¡zar', 0, 'published', 0, 0),
(86, 16, 16, 'Phyton', '', 'QuÃ¡zar', '2018-10-05', 'csm_project_php.jpg', '<p>123</p>', 'Phyton, QuÃ¡zar', 0, 'published', 0, 0),
(87, 2, 16, 'Javascript', '', 'QuÃ¡zar', '2018-10-05', '', '<p>wdwqd</p>', 'Javascript', 0, 'published', 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `user_id` int(3) NOT NULL,
  `username` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_firstname` varchar(255) NOT NULL,
  `user_lastname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_image` text NOT NULL,
  `user_role` varchar(255) NOT NULL,
  `randSalt` varchar(255) NOT NULL DEFAULT '$2y$10$iusesomecrazystrings22',
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`user_id`, `username`, `user_password`, `user_firstname`, `user_lastname`, `user_email`, `user_image`, `user_role`, `randSalt`, `token`) VALUES
(16, 'QuÃ¡zar', '$2y$10$vQ0hGcL2/VlSjgokeHVtwuoFT7UelqHaqBrx9Kgqty6TdeXryripK', 'Damian', 'KrzemiÅ„ski', 'damian.krzeminski13@gmail.com', '', 'admin', '$2y$10$iusesomecrazystrings22', 'ac3622e109eaec9ec06b123e41c3b422a896d1911634d0898a9d96bf62575e5207cf88a6587a75a6457f7f73d5b0218561d2'),
(17, 'QuÃ¡zarV13', '$2y$10$9Roxh8vVlt3AepeCXNYUze06XhU/6rPHws.nBbO6508XaIoa3XQH2', 'Damian', 'KrzemiÅ„ski', 'damian.krzeminski13@gmail.com', '', 'admin', '$2y$10$iusesomecrazystrings22', 'ac3622e109eaec9ec06b123e41c3b422a896d1911634d0898a9d96bf62575e5207cf88a6587a75a6457f7f73d5b0218561d2'),
(18, 'QuÃ¡zarS', '$2y$10$JNG0WD5c8bSzn.5/sf23xOtzqbhQNjeLecuctED0vm.YdREGWHIH6', 'Damian', 'KrzemiÅ„ski', 'damian.krzeminski13@gmail.com', '', 'subscriber', '$2y$10$iusesomecrazystrings22', 'ac3622e109eaec9ec06b123e41c3b422a896d1911634d0898a9d96bf62575e5207cf88a6587a75a6457f7f73d5b0218561d2'),
(19, 'QuÃ¡zarV13S', '$2y$10$5Pe4Fvo5XUT4JAGVg.ImW.DQy/WVdXCPnHXeSZJDqk7q5y4jJZGSS', 'Damian', 'KrzemiÅ„ski', 'damian.krzeminski13@gmail.com', '', 'subscriber', '$2y$10$iusesomecrazystrings22', 'ac3622e109eaec9ec06b123e41c3b422a896d1911634d0898a9d96bf62575e5207cf88a6587a75a6457f7f73d5b0218561d2'),
(20, 'saddd253', '$2y$10$XXZuaUex1GgUvkMcjYGmf.fxC3fGOB9UNnfDh./EFyI1RwFqSyMFK', 'Damiane', 'KrzemiÅ„ski324', 'damian234.krzeminski13@gmail.com', '', 'subscriber', '$2y$10$iusesomecrazystrings22', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_online`
--

CREATE TABLE `users_online` (
  `id` int(11) NOT NULL,
  `session` varchar(255) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `users_online`
--

INSERT INTO `users_online` (`id`, `session`, `time`) VALUES
(2, 'j94fla5koa27pk5r79ov17o240', 1538066802),
(3, 'l3il1ulljrp24pe5kttn6ossnn', 1538139916),
(4, '54gvgolqub091sa0u909160qi9', 1538153898),
(5, 'kj85srssa1vkcfueji5jme1ls3', 1538161002),
(6, 'qbjnheorln601snhtt75sh32db', 1538234381),
(7, 'mkia166past94taaevobv04j0a', 1538227462),
(8, 'j89i6cfs2df7rm003sps6supcb', 1538321084),
(9, 'uheuo7050h9kgh52bs8gqqh623', 1538411390),
(10, 'vgi8ul2l8pjjcm6eenepr7tdbi', 1538498125),
(11, 'fq9cnbv9bq061vkahhhgbnrq89', 1538490651),
(12, 'drei6mrqsthp6iq9l7lpasro38', 1538491687),
(13, 't4bhr8sjb7m2flsbjbf60ebkrj', 1538591823),
(14, 'f6rjigd27aduls90e0ggr7dujc', 1538574773),
(15, 'f493qmeuao4vkeq1hmghbq53hu', 1538575184),
(16, '6nn7oboogamffr49mm0bt7d07k', 1538677823),
(17, '7d5qbog9ebiauqppje0it6vcsh', 1538742293),
(18, 'f6udc4cp9b2vg3dmnnc7qmclcp', 1541325403),
(19, 'mdl6umsdlvn6kk0mj7ks15vcju', 1608572346),
(20, '5qd3fdhktp4tu94bm4kj31dq96', 1608657671),
(21, '8mjmidnk35q1jhglsjtop39ai1', 1608917333);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indeksy dla tabeli `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indeksy dla tabeli `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indeksy dla tabeli `users_online`
--
ALTER TABLE `users_online`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT dla tabeli `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT dla tabeli `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT dla tabeli `users_online`
--
ALTER TABLE `users_online`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
