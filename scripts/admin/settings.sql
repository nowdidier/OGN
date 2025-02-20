--
-- Структура таблицы `settings`
-- Всё будет меняться, на данный момент нет смысла делать запрос. 
-- Данный функционал не поддерживается. В стадии разработки.
-- 
 
CREATE TABLE `settings` (
  `val` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `value` text COLLATE utf8mb4_general_ci NOT NULL,
  UNIQUE KEY `val` (`val`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `settings` (`val`, `value`) VALUES
('url', 'https://agezweho.com'),
('email', 'mail@agezweho.com'),
('name', 'agezweho'),
('title', 'AGEZWEHO — Umuryango (porogaramu y\'amablog menshi)'),
('img_path', '/assets/images/agezweho.jpg'),
('img_path_web', '/assets/images/libarea-web.png'),
('banner_title', 'AGEZWEHO — Umuryango'),
('banner_desc', 'Inyandiko zijyanye n\'ibyo ukunda. Ibiganiro, ibibazo n\'ibisubizo, ibisobanuro. Porogaramu y\'amablog menshi'),

('feed_title', 'AGEZWEHO — Umuryango (porogaramu y\'amablog menshi)'),
('feed_desc', 'Inyandiko zijyanye n\'ibyo ukunda, urutonde rw\'amakuru, amablog. Urutonde rwa websites. Urubuga rw\'amablog y\'itsinda, porogaramu y\'amablog menshi (umuryango) AGEZWEHO'),
('top_title', 'AGEZWEHO — Ibisohoka Bikunzwe cyane'),
('top_desc', 'Urutonde rw\'ibisohoka bikunzwe cyane mu muryango (hashingiwe ku mubare w\'ibisubizo). Inyandiko zijyanye n\'ibyo ukunda. Ibiganiro, ibibazo n\'ibisubizo, ibisobanuro. Porogaramu y\'umuryango AGEZWEHO'),
('all_title', 'AGEZWEHO — Ibisohoka Byose'),
('all_desc', 'Urutonde rw\'ibisohoka byose mu muryango. Porogaramu y\'umuryango AGEZWEHO'),

('ads_home_post', ''),
('ads_home_sidebar', ''),
('ads_home_menu', ''),
('ads_post_sidebar', ''),
('ads_post_footer', ''),
('ads_post_answer', ''),
('ads_catalog_home', ''),
('ads_catalog_cat_sidebar', ''),

('count_like_feed', 1),
('type_post_feed', 'classic');