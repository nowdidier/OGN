-- Imiterere ya table ya `settings`
-- Ibisabwa by'ingenzi kuri interineti mu Kinyarwanda
 
CREATE TABLE `settings` (
  `val` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `value` text COLLATE utf8mb4_general_ci NOT NULL,
  UNIQUE KEY `val` (`val`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `settings` (`val`, `value`) VALUES
('url', 'https://agezweho.com'),
('email', 'info@agezweho.com'),
('name', 'Agezweho'),
('title', 'AGEZWEHO — Ibirimo by\'ingenzi'),
('img_path', '/assets/images/AGEZWEHO.jpg'),
('img_path_web', '/assets/images/AGEZWEHO-web.png'),
('banner_title', 'AGEZWEHO — Amakuru y\'uyu munsi'),
('banner_desc', 'Aha ni ho ushobora kubona amakuru agezweho mu Rwanda'),
('feed_title', 'AGEZWEHO — Ibiganiro'),
('feed_desc', 'Ibiganiro, amakuru n\'inyandiko mu Kinyarwanda; urutonde rw\'ibiganiro n\'amyirondoro'),
('top_title', 'AGEZWEHO — Ibisohoka Byiza'),
('top_desc', 'Ibisohoka bihanitse hashingiwe ku mibare n\'ibitekerezo by\'abakoresha'),
('all_title', 'AGEZWEHO — Ibisohoka Byose'),
('all_desc', 'Ibisohoka byose biri ku rubuga rwacu mu Kinyarwanda'),
('ads_home_post', ''),
('ads_home_sidebar', ''),
('ads_home_menu', ''),
('ads_post_sidebar', ''),
('ads_post_footer', ''),
('ads_post_answer', ''),
('ads_catalog_home', ''),
('ads_catalog_cat_sidebar', ''),
('count_like_feed', '1'),
('type_post_feed', 'classic');