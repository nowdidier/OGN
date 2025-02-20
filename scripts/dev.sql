--
-- Структура таблицы `audits`
--

CREATE TABLE `audits` (
  `id` int(11) NOT NULL,
  `action_type` varchar(32) DEFAULT NULL,
  `type_belonging` varchar(16) NOT NULL DEFAULT 'audit' COMMENT 'audit or repost)',
  `content_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `add_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_flag` tinyint(1) DEFAULT 0 COMMENT 'Состояние прочтения'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `badges`
--

CREATE TABLE `badges` (
  `badge_id` int(11) NOT NULL,
  `badge_icon` varchar(550) NOT NULL,
  `badge_tl` int(6) DEFAULT NULL,
  `badge_score` int(6) DEFAULT NULL,
  `badge_title` varchar(150) NOT NULL,
  `badge_description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `badges`
--

INSERT INTO `badges` (`badge_id`, `badge_icon`, `badge_tl`, `badge_score`, `badge_title`, `badge_description`) VALUES
(1, '<span title=\"Yatanze amakuru y\'ikosa ryashimishije itsinda ry\'urubuga.\"><svg class=\"icons icon-base red\"><use xlink:href=\"/assets/svg/icons.svg#bug\"></use></svg></span>', 0, 0, 'Umugenzuzi', 'Amakuru y\'ikosa ryashimishije itsinda ry\'urubuga.');

-- --------------------------------------------------------

--
-- Структура таблицы `badges_user`
--

CREATE TABLE `badges_user` (
  `bu_id` int(11) NOT NULL,
  `bu_badge_id` int(6) NOT NULL,
  `bu_user_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment_post_id` int(11) NOT NULL DEFAULT 0,
  `comment_parent_id` int(11) NOT NULL DEFAULT 0,
  `comment_user_id` int(11) NOT NULL DEFAULT 0,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `comment_modified` timestamp NOT NULL DEFAULT '2020-12-31 00:00:00',
  `comment_published` tinyint(1) NOT NULL DEFAULT 1,
  `comment_ip` varbinary(16) DEFAULT NULL,
  `comment_votes` smallint(6) NOT NULL DEFAULT 0,
  `comment_content` text NOT NULL,
  `comment_lo` int(11) NOT NULL DEFAULT 0,
  `comment_is_mobile` tinyint(1) NOT NULL DEFAULT 0,
  `comment_is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`comment_id`, `comment_post_id`, `comment_parent_id`, `comment_user_id`, `comment_date`, `comment_modified`, `comment_published`, `comment_ip`, `comment_votes`, `comment_content`, `comment_lo`, `comment_is_mobile`, `comment_is_deleted`) VALUES
(1, 3, 0, 1, '2021-04-28 01:41:27', '2020-12-29 06:00:00', 1, 0x3132372e302e302e31, 0, 'Igisubizo cya mbere mu ngingo', 0, 0, 0),
(2, 1, 0, 2, '2021-06-30 04:34:52', '2021-08-14 19:50:53', 1, 0x3132372e302e302e31, 0, 'Birashimishije, murakoze. Mwaribagiwe kuvuga ko ushobora kubaza ibibazo mu kiganiro (umuhanda uri mu footer) y\'uru rubuga.', 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `facets`
--

CREATE TABLE `facets` (
  `facet_id` int(11) NOT NULL,
  `facet_title` varchar(64) DEFAULT NULL,
  `facet_description` varchar(255) DEFAULT NULL,
  `facet_short_description` varchar(160) DEFAULT NULL,
  `facet_info` text DEFAULT NULL,
  `facet_slug` varchar(32) DEFAULT NULL,
  `facet_img` varchar(255) DEFAULT 'facet-default.png',
  `facet_cover_art` varchar(255) DEFAULT 'cover_art.jpeg',
  `facet_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `facet_seo_title` varchar(255) DEFAULT NULL,
  `facet_entry_policy` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Политика вступления',
  `facet_view_policy` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Политика просмотра (1 - после подписки)',
  `facet_merged_id` int(11) NOT NULL DEFAULT 0 COMMENT 'С кем слит',
  `facet_top_level` tinyint(1) NOT NULL DEFAULT 0,
  `facet_user_id` int(11) NOT NULL DEFAULT 1,
  `facet_tl` tinyint(1) NOT NULL DEFAULT 0,
  `facet_post_related` varchar(255) DEFAULT NULL,
  `facet_the_day` tinyint(1) NOT NULL DEFAULT 0,
  `facet_focus_count` int(11) NOT NULL DEFAULT 0,
  `facet_count` int(11) NOT NULL DEFAULT 0,
  `facet_sort` int(11) NOT NULL DEFAULT 0,
  `facet_type` varchar(32) NOT NULL DEFAULT 'topic' COMMENT 'Topic, Group or Blog...',
  `facet_is_comments` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Are comments closed (posts, websites...)?',
  `facet_is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `facets`
--

INSERT INTO `facets` (`facet_id`, `facet_title`, `facet_description`, `facet_short_description`, `facet_info`, `facet_slug`, `facet_img`, `facet_cover_art`, `facet_date`, `facet_seo_title`, `facet_entry_policy`, `facet_view_policy`, `facet_merged_id`, `facet_top_level`, `facet_user_id`, `facet_tl`, `facet_post_related`, `facet_the_day`, `facet_focus_count`, `facet_count`, `facet_sort`, `facet_type`, `facet_is_comments`, `facet_is_deleted`) VALUES
(1, 'SEO', 'Gushakisha imikorere ni urukurikirane rw\'ingamba zo kunoza imbere n\'inyuma kugirango urubuga ruzamuke mu myanya y\'ibyavuye mu bushakashatsi.', 'Ibisobanuro bigufi by\'ingingo...', 'Gushakisha imikorere ni uburyo bwo gukoresha amategeko y\'ubushakashatsi kugirango urubuga rube urwa mbere mu nganda kugirango rufate inyungu z\'ikirango. SEO irimo SEO y\'inyuma na SEO y\'imbere. SEO ni uburyo bwo kubona umubare munini w\'abashyitsi b\'ubuntu bavuye mu bushakashatsi, gutegura neza imiterere y\'urubuga, gahunda yo kubaka ibikubiyemo, imikoranire n\'umukoresha n\'itumanaho, impapuro n\'ibindi, kugirango urubuga rube rwiza ku mahame y\'ubushakashatsi.', 'seo', 't-1-1625149922.jpeg', 'cover_art.jpeg', '2021-06-27 06:29:20', 'Gushakisha imikorere (SEO)', 0, 0, 0, 0, 1, 0, '1,2,3', 0, 1, 2, 0, 'topic', 0, 0),
(2, 'Imbuga zishimishije', 'Imbuga zishimishije kuri interineti. Ibisobanuro, ibikoresho bishimishije, ibisobanuro. Inyandiko.', 'Ibisobanuro bigufi by\'ingingo...', 'Imbuga zishimishije kuri interineti. Ibisobanuro, ibikoresho bishimishije, ibisobanuro. Inyandiko. Urupapuro rw\'ibanze... Mu iterambere...', 'sites', 't-2-1625149821.jpeg', 'cover_art.jpeg', '2021-06-27 06:29:20', 'Imbuga zishimishije', 0, 0, 0, 0, 1, 0, '3', 0, 1, 2, 0, 'topic', 0, 0),
(3, 'Iterambere rya interineti', 'Iterambere rya interineti ni akazi gafitanye isano no gukora urubuga rwa interineti (World Wide Web) cyangwa intranet (urubuga rwihariye).', 'Iterambere rya interineti', 'Iterambere rya interineti ni akazi gafitanye isano no gukora urubuga rwa interineti (World Wide Web) cyangwa intranet (urubuga rwihariye). Iterambere rya interineti rishobora kuva ku gukora urupapuro rworoshye rufite inyandiko ifunguye kugeza ku mishinga y\'interineti igoye, ubucuruzi bwa interineti n\'imbuga nkoranyambaga.', 'web-development', 'topic-default.png', 'cover_art.jpeg', '2021-11-04 08:04:41', 'Iterambere rya interineti', 0, 0, 0, 0, 1, 0, '1,2,3', 0, 1, 1, 0, 'topic', 0, 0),
(4, 'Amakuru', 'Amakuru (ubufasha). Iki gice kirimo amakuru y\'ubufasha.', 'Amakuru', 'Amakuru (ubufasha). Iki gice kirimo amakuru y\'ubufasha.', 'info', 'facet-default.png', 'cover_art.jpeg', '2021-12-21 23:07:54', 'Amakuru', 0, 0, 0, 0, 1, 0, '', 0, 1, 0, 0, 'section', 0, 0),
(5, 'Interineti', 'Interineti - urubuga rw\'isi yose. Igitabo cy\'imbuga, ibikoresho by\'ingirakamaro.', 'Interineti - byose', 'Interineti - urubuga rw\'isi yose. Igitabo cy\'imbuga, ibikoresho by\'ingirakamaro.', 'internet', 'facet-default.png', 'cover_art.jpeg', '2022-02-09 17:52:33', 'Interineti - byose', 0, 0, 0, 0, 1, 0, '', 0, 1, 0, 0, 'category', 0, 0),
(6, 'Amakuru y\'ubufasha', 'Ibitabo n\'ibitabo, amakuru y\'ubufasha. Imbuga z\'amakuru.', 'Amakuru y\'ubufasha', NULL, 'reference', 'facet-default.png', 'cover_art.jpeg', '2022-02-09 17:58:47', 'Amakuru y\'ubufasha', 0, 0, 0, 0, 1, 0, NULL, 0, 1, 0, 0, 'category', 0, 0),
(7, 'Umutekano', 'Imbuga zerekeye umutekano kuri interineti. Virusi n\'ibikoresho by\'ubwirinzi n\'ibindi.', 'Umutekano', NULL, 'security', 'facet-default.png', 'cover_art.jpeg', '2022-02-09 18:02:11', 'Umutekano', 0, 0, 0, 0, 1, 0, NULL, 0, 1, 0, 0, 'category', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `facets_items_relation`
--

CREATE TABLE `facets_items_relation` (
  `relation_facet_id` int(11) DEFAULT 0,
  `relation_item_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `facets_items_relation`
--

INSERT INTO `facets_items_relation` (`relation_facet_id`, `relation_item_id`) VALUES
(5, 2),
(5, 1),
(5, 3),
(6, 3),
(6, 5),
(5, 4),
(7, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `facets_matching`
--

CREATE TABLE `facets_matching` (
  `matching_parent_id` int(11) DEFAULT NULL,
  `matching_chaid_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `facets_matching`
--

INSERT INTO `facets_matching` (`matching_parent_id`, `matching_chaid_id`) VALUES
(2, 3),
(3, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `facets_merge`
--

CREATE TABLE `facets_merge` (
  `merge_id` int(11) NOT NULL,
  `merge_add_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `merge_source_id` int(11) NOT NULL DEFAULT 0,
  `merge_target_id` int(11) NOT NULL DEFAULT 0,
  `merge_user_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `facets_posts_relation`
--

CREATE TABLE `facets_posts_relation` (
  `relation_facet_id` int(11) DEFAULT 0,
  `relation_post_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `facets_posts_relation`
--

INSERT INTO `facets_posts_relation` (`relation_facet_id`, `relation_post_id`) VALUES
(1, 1),
(2, 2),
(2, 4),
(1, 4),
(3, 5),
(4, 6),
(4, 7),
(3, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `facets_relation`
--

CREATE TABLE `facets_relation` (
  `facet_parent_id` int(11) DEFAULT NULL,
  `facet_chaid_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `facets_relation`
--

INSERT INTO `facets_relation` (`facet_parent_id`, `facet_chaid_id`) VALUES
(3, 1),
(5, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `facets_signed`
--

CREATE TABLE `facets_signed` (
  `signed_id` int(11) NOT NULL,
  `signed_facet_id` int(11) NOT NULL,
  `signed_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `facets_signed`
--

INSERT INTO `facets_signed` (`signed_id`, `signed_facet_id`, `signed_user_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(4, 3, 1),
(5, 4, 1),
(6, 5, 1),
(7, 6, 1),
(8, 7, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `facets_types`
--

CREATE TABLE `facets_types` (
  `type_id` int(11) NOT NULL,
  `type_code` varchar(50) NOT NULL,
  `type_lang` varchar(50) NOT NULL,
  `type_title` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `facets_types`
--

INSERT INTO `facets_types` (`type_id`, `type_code`, `type_lang`, `type_title`) VALUES
(1, 'topic', 'topic', 'Ingingo'),
(2, 'blog', 'blog', 'Blog'),
(3, 'section', 'section', 'Igice'),
(4, 'category', 'category', 'Ibyiciro');

-- --------------------------------------------------------

--
-- Структура таблицы `facets_users_team`
--

CREATE TABLE `facets_users_team` (
  `team_id` int(11) NOT NULL,
  `team_facet_id` int(11) NOT NULL,
  `team_user_id` int(11) NOT NULL,
  `team_user_access` int(4) NOT NULL DEFAULT 0,
  `team_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `id` mediumint(9) NOT NULL,
  `user_id` mediumint(9) NOT NULL,
  `tid` int(11) NOT NULL,
  `action_type` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `file_id` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` varchar(32) DEFAULT NULL,
  `file_content_id` int(11) UNSIGNED DEFAULT NULL,
  `file_user_id` int(11) UNSIGNED DEFAULT NULL,
  `file_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `files`
--

INSERT INTO `files` (`file_id`, `file_path`, `file_type`, `file_content_id`, `file_user_id`, `file_date`, `file_is_deleted`) VALUES
(1, '2021/c-1638777119.webp', 'post', 0, 1, '2021-12-05 10:52:00', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `action_type` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `folders_relation`
--

CREATE TABLE `folders_relation` (
  `id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL COMMENT 'ID папки (folders)',
  `action_type` varchar(32) NOT NULL COMMENT 'Тип контента',
  `tid` int(11) NOT NULL COMMENT 'id контента',
  `user_id` int(11) NOT NULL COMMENT 'id кто добавил'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `invitations`
--

CREATE TABLE `invitations` (
  `invitation_id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) DEFAULT 0,
  `invitation_code` varchar(32) DEFAULT NULL,
  `invitation_email` varchar(100) DEFAULT NULL,
  `invitation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `add_ip` varchar(45) DEFAULT NULL,
  `active_expire` tinyint(1) DEFAULT 0,
  `active_time` datetime DEFAULT NULL,
  `active_ip` varchar(45) DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 0,
  `active_uid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_url` varchar(255) DEFAULT NULL,
  `item_slug` varchar(255) DEFAULT NULL,
  `item_domain` varchar(255) DEFAULT NULL,
  `item_title` varchar(255) DEFAULT NULL,
  `item_content` text DEFAULT NULL,
  `item_title_soft` varchar(255) DEFAULT NULL,
  `item_content_soft` text DEFAULT NULL,
  `item_published` tinyint(1) NOT NULL DEFAULT 1,
  `item_user_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Who added',
  `item_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `item_modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `item_is_forum` tinyint(1) DEFAULT NULL COMMENT 'The site has a forum',
  `item_is_portal` tinyint(1) DEFAULT NULL COMMENT 'This is a portal',
  `item_is_blog` tinyint(1) DEFAULT NULL COMMENT 'This is a blog',
  `item_is_reference` tinyint(1) DEFAULT NULL COMMENT 'Is this a reference site',
  `item_is_goods` tinyint(1) DEFAULT NULL COMMENT 'The site contains goods and services',
  `item_is_soft` tinyint(1) DEFAULT NULL COMMENT 'There is a program (script)',
  `item_is_github` tinyint(1) DEFAULT NULL COMMENT 'The site is on GitHub',
  `item_github_url` varchar(255) DEFAULT NULL COMMENT 'URL on GitHub',
  `item_telephone` varchar(55) DEFAULT NULL COMMENT 'Contact phone',
  `item_email` varchar(55) DEFAULT NULL COMMENT 'Public email address',
  `item_vk` varchar(55) DEFAULT NULL COMMENT 'Page in VKontakte',
  `item_telegram` varchar(55) DEFAULT NULL COMMENT 'Page in Telegram',
  `item_post_related` varchar(255) DEFAULT NULL,
  `item_focus_count` int(11) DEFAULT NULL,
  `item_close_replies` tinyint(1) DEFAULT NULL,
  `item_votes` int(11) NOT NULL DEFAULT 0,
  `item_count` int(11) NOT NULL DEFAULT 1,
  `item_following_link` int(11) NOT NULL DEFAULT 1,
  `item_poll` smallint(6) NOT NULL DEFAULT 0 COMMENT 'Для опроса',
  `item_is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `items`
--

INSERT INTO `items` (`item_id`, `item_url`, `item_slug`, `item_domain`, `item_title`, `item_content`, `item_title_soft`, `item_content_soft`, `item_published`, `item_user_id`, `item_date`, `item_modified`, `item_is_forum`, `item_is_portal`, `item_is_blog`, `item_is_reference`, `item_is_goods`, `item_is_soft`, `item_is_github`, `item_github_url`, `item_telephone`, `item_email`, `item_vk`, `item_telegram`, `item_post_related`, `item_focus_count`, `item_close_replies`, `item_votes`, `item_count`, `item_following_link`, `item_poll`, `item_is_deleted`) VALUES
(1, 'https://agezweho.ru', 'agezweho', 'agezweho.ru', '«AGEZWEHO» — umuryango w\'abantu bafite inyungu', 'Ibyanditswe byiza by\'umunsi. Ingingo, ibitabo by\'abantu ku giti cyabo, amatsinda. Igitabo cy\'imbuga n\'ibikoresho, gushakisha..', 'AGEZWEHO', 'Ikiganiro (forum) n\'urubuga rwa Q&A. Umuryango ushingiye kuri PHP Micro-Framework HLEB. (Zhihu, Quora clone)', 1, 1, '2021-06-20 13:35:02', '2023-02-16 08:48:43', NULL, NULL, NULL, NULL, NULL, 1, 1, 'https://github.com/AGEZWEHO/agezweho', NULL, NULL, NULL, NULL, '2', 0, 0, 1, 1, 1, 0, 0),
(2, 'https://github.com', 'github', 'github.com', '«GitHub» — urubuga rwa interineti rwo kwakira imishinga ya IT', 'Urubuga rwa interineti, urubuga rwo kwakira abahanga mu gukora porogaramu rishingiye ku buryo bwo kugenzura imishinga ya Git. Verisiyo z\'ubuntu, verisiyo zishyurwa z\'ububiko.', '', '', 1, 1, '2021-11-02 17:30:40', '2023-02-16 08:48:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', 0, 0, 1, 1, 1, 0, 0),
(3, 'https://hleb2framework.ru', 'phphleb', 'hleb2framework.ru', '«HLEB» — micro-framework', 'Ibisobanuro n\'isobanuro rya Micro-Framework ikoresha ishyirwa mu bikorwa rya MVC kuri PHP. Gushyiraho, guhitamo, imiterere y\'umushinga. Guhindura, abagenzuzi n\'ibishushanyo.', 'HLEB', 'Ikiranga micro-programme HLEB ni ubuto bw\'ikode n\'umuvuduko w\'akazi. Guhitamo iyi framework byemerera gutangiza umushinga wuzuye mu gihe gito kandi utagombye gusoma cyane inyandiko; biroroshye, byoroshye kandi byihuse. Icyarimwe, ikemura ibibazo bisanzwe, nko guhindura, kwimura ibikorwa ku bagenzuzi, gushyigikira icyitegererezo, ni ukuvuga ishyirwa mu bikorwa rya MVC. Ibi nibyo bike bikenewe kugirango hatangizwe porogaramu byihuse.', 1, 1, '2021-11-08 02:02:24', '2023-02-16 08:48:43', NULL, NULL, NULL, NULL, NULL, 1, 1, 'https://github.com/phphleb/hleb', NULL, NULL, NULL, NULL, '', 0, 0, 1, 1, 1, 0, 0),
(4, 'https://ispserver.ru', 'ispserver', 'ispserver.ru', '«ISPserveru» — umuhoza', 'Urubuga rwa interineti, VPS n\'ibikoresho byihariye mu bigo bitatu by\'amakuru i Moscou no mu Budage. Amakuru yerekeye gucunga ISPmanager. Amakuru, itumanaho.', '', '', 1, 1, '2021-11-12 00:50:54', '2023-02-16 08:48:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', 0, 0, 1, 1, 1, 0, 0),
(5, 'https://sourceforge.net', 'sourceforge', 'sourceforge.net', '«Sourceforge» — imishinga ifite isoko ifunguye', 'Urutonde rw\'imishinga ifite isoko ifunguye. Urutonde rw\'imishinga ikeneye ubufasha. Uburyo bwo gukuramo ibikoresho bitandukanye. Amakuru mu cyongereza.', '', '', 1, 1, '2021-11-12 01:12:18', '2023-02-16 08:48:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', 0, 0, 1, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `items_signed`
--

CREATE TABLE `items_signed` (
  `signed_id` int(11) NOT NULL,
  `signed_item_id` int(11) NOT NULL,
  `signed_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `items_status`
--

CREATE TABLE `items_status` (
  `status_id` int(11) NOT NULL,
  `status_item_id` int(11) NOT NULL,
  `status_response` varchar(15) NOT NULL,
  `status_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `message_sender_id` int(11) DEFAULT NULL COMMENT 'Отправитель',
  `message_dialog_id` int(11) DEFAULT NULL,
  `message_content` text DEFAULT NULL,
  `message_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `message_modified` timestamp NULL DEFAULT NULL,
  `message_sender_remove` tinyint(1) DEFAULT 0,
  `message_recipient_remove` tinyint(1) DEFAULT 0,
  `message_receipt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `messages_dialog`
--

CREATE TABLE `messages_dialog` (
  `dialog_id` int(11) NOT NULL,
  `dialog_sender_id` int(11) DEFAULT NULL COMMENT 'Отправитель',
  `dialog_sender_unread` int(11) DEFAULT NULL COMMENT 'Отправитель, 0 непрочитано',
  `dialog_recipient_id` int(11) DEFAULT NULL COMMENT 'Получатель',
  `dialog_recipient_unread` int(11) DEFAULT NULL COMMENT 'Получатель, 0 непрочитано',
  `dialog_add_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `dialog_update_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `dialog_sender_count` int(11) DEFAULT NULL COMMENT 'Отправитель кол.',
  `dialog_recipient_count` int(11) DEFAULT NULL COMMENT 'Получатель кол.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL COMMENT 'Отправитель',
  `recipient_id` int(11) DEFAULT 0 COMMENT 'Получает ID',
  `action_type` int(4) DEFAULT NULL COMMENT 'Тип оповещения',
  `url` varchar(255) DEFAULT NULL COMMENT 'URL источника',
  `add_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_flag` tinyint(1) DEFAULT 0 COMMENT 'Состояние прочтения',
  `is_deleted` tinyint(1) UNSIGNED DEFAULT 0 COMMENT 'Удаление'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `polls`
--

CREATE TABLE `polls` (
  `poll_id` int(11) NOT NULL,
  `poll_title` varchar(255) NOT NULL,
  `poll_user_id` int(11) NOT NULL,
  `poll_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `poll_modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `poll_tl` tinyint(1) NOT NULL DEFAULT 0,
  `poll_is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `poll_is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `polls_answers`
--

CREATE TABLE `polls_answers` (
  `answer_id` int(11) NOT NULL,
  `answer_question_id` int(11) DEFAULT NULL,
  `answer_title` varchar(255) NOT NULL,
  `answer_votes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `polls_votes`
--

CREATE TABLE `polls_votes` (
  `vote_id` int(11) NOT NULL,
  `vote_question_id` int(11) NOT NULL,
  `vote_answer_id` int(11) NOT NULL,
  `vote_user_id` int(11) NOT NULL,
  `vote_ip` varchar(15) NOT NULL,
  `vote_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_slug` varchar(128) NOT NULL,
  `post_feature` smallint(6) NOT NULL DEFAULT 0 COMMENT '0 - discussion, 1 - question...',
  `post_type` varchar(32) NOT NULL DEFAULT 'post' COMMENT 'Post, page...',
  `post_translation` smallint(1) NOT NULL DEFAULT 0,
  `post_draft` smallint(1) NOT NULL DEFAULT 0,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_published` tinyint(1) NOT NULL DEFAULT 1,
  `post_nsfw` tinyint(1) NOT NULL DEFAULT 0,
  `post_user_id` int(10) UNSIGNED NOT NULL,
  `post_ip` varbinary(16) DEFAULT NULL,
  `post_after` smallint(6) NOT NULL DEFAULT 0 COMMENT 'id первого ответа',
  `post_votes` smallint(6) NOT NULL DEFAULT 0,
  `post_karma` smallint(6) NOT NULL DEFAULT 0,
  `post_comments_count` int(11) DEFAULT 0,
  `post_hits_count` int(11) DEFAULT 0,
  `post_content` text NOT NULL,
  `post_content_img` varchar(255) DEFAULT NULL,
  `post_thumb_img` varchar(255) DEFAULT NULL,
  `post_related` varchar(255) DEFAULT NULL,
  `post_merged_id` int(11) NOT NULL DEFAULT 0 COMMENT 'id с чем объединен',
  `post_is_recommend` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Рекомендованные посты',
  `post_closed` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 - пост закрыт',
  `post_poll` smallint(6) NOT NULL DEFAULT 0 COMMENT 'Для опросов',
  `post_tl` smallint(1) NOT NULL DEFAULT 0 COMMENT 'Видимость по уровню доверия',
  `post_lo` int(11) NOT NULL DEFAULT 0 COMMENT 'Id лучшего ответа',
  `post_top` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 - пост поднят',
  `post_hidden` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Скрытый пост',
  `post_url` varchar(255) DEFAULT NULL,
  `post_url_domain` varchar(255) DEFAULT NULL,
  `post_focus_count` int(11) DEFAULT 0,
  `post_is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`post_id`, `post_title`, `post_slug`, `post_feature`, `post_type`, `post_translation`, `post_draft`, `post_date`, `post_modified`, `post_published`, `post_nsfw`, `post_user_id`, `post_ip`, `post_after`, `post_votes`, `post_karma`, `post_comments_count`, `post_hits_count`, `post_content`, `post_content_img`, `post_thumb_img`, `post_related`, `post_merged_id`, `post_is_recommend`, `post_closed`, `post_poll`, `post_tl`, `post_lo`, `post_top`, `post_hidden`, `post_url`, `post_url_domain`, `post_focus_count`, `post_is_deleted`) VALUES
(1, 'Igisubizo ku bibazo bimwe na bimwe (FAQ)', 'answer-qa', 0, 'post', 0, 0, '2021-02-26 06:08:09', '2024-04-05 13:39:28', 1, 0, 1, 0x3132372e302e302e31, 0, 0, 0, 1, 7, 'Icyitegererezo cy\'inyandiko ku rupapuro rw\'urubuga rw\'urubuga rukoreshwa mu gika cya mbere cy\'inyandiko. Dufata igika cya mbere tugakora icyitegererezo. Urubuga rukoreshwa na MVC model, niba hari uwamenye, ntabwo bigoye cyane gusobanukirwa.\r\n\r\n### Aho amadosiye y\'urubuga ari?\r\n\r\nAmadosiye y\'urubuga ari mu gitabo `config` (mu mizi y\'urubuga).\r\n\r\n### Aho ibishushanyo mbonera by\'urubuga biri?\r\n\r\n```\r\n/resources/views/default\r\n```\r\n\r\n### Nigute nahindura ururimi rw\'urubuga?\r\n\r\nBy default, urubuga rukoreshwa mu rurimi rw\'ikirusiya.\r\n\r\nIbisobanuro biri mu madosiye: `/app/Language/`\r\n\r\nUrashobora guhindura indimi, mu madosiye (configuration): `/config/general.php` \r\n\r\nShakisha:\r\n```php\r\n\'lang\' => \'ru\',\r\n```\r\n\r\n---\r\n\r\nBy default, urubuga rukoreshwa mu rurimi rw\'ikirusiya.\r\n\r\nIbisobanuro biri mu madosiye: `general.php` find:\r\n\r\n```php\r\n\'lang\' => \'ru\',\r\n```\r\n\r\nIbyo bisobanuro ubwabyo bibikwa: `/app/Language/`', '', NULL, '2', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0),
(2, 'Aho ushobora gusoma inyandiko?', 'docs-post', 0, 'post', 0, 0, '2021-02-26 06:15:58', '2021-11-04 05:26:31', 1, 0, 2, 0x3132372e302e302e31, 0, 1, 0, 0, 8, 'Urupapuro rw\'inyandiko AGEZWEHO ruri mu iterambere... \r\n\r\n[https://agezweho.com/](https://agezweho.com/)\r\n\r\nNirangira, bizatangazwa by\'umwihariko. Urubuga ubwabwo rwakozwe kuri PHP Micro-framework HLEB. Ibyingenzi byose, urashobora kubisanga kuri uru rubuga:\r\n\r\n[https://hleb2framework.ru/](https://hleb2framework.ru/)\r\n\r\n', '', NULL, '1', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0),
(3, 'Medium — urubuga rwo gukora ibikubiyemo', 'medium-where-good-ideas-find-you', 0, 'post', 0, 0, '2021-04-27 22:35:13', '2024-03-02 23:01:04', 1, 0, 1, 0x3132372e302e302e31, 0, 0, 0, 1, 2, 'Medium ni urubuga rwo gukora ibikubiyemo, rwasizweho n\'umwe mu bashinze Blogger na Twitter Evan Williams. Ibigo byinshi bikoresha Medium nk\'urubuga rwo gutangaza...', '2021/c-1624954734.webp', NULL, '', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0),
(4, 'Bee Network 🥳 — Cryptocurrency Pi-Network', 'bee-network-e-mdash-kriptovalyuta-pi-network', 0, 'post', 0, 0, '2021-12-05 07:52:00', '2024-04-05 13:41:24', 1, 0, 1, 0x3132372e302e302e31, 0, 2, 0, 0, 4, 'Bee Network  🥳  —  Cryptocurrency Pi-Network', '2021/c-1638777119.webp', '', '', 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1, 0),
(5, 'Amahirwe yihishe mu guhitamo umwirondoro', 'skrytye-vozmozhnosti-v-nastrojkah-profilya', 0, 'post', 0, 0, '2021-12-07 10:44:23', '2021-12-07 10:44:23', 1, 0, 1, 0x3132372e302e302e31, 0, 2, 0, 0, 1, 'Niba turebye mu bubiko bw\'amakuru, mu gitabo cy\'abakoresha (users) turashobora kubona imirima ibiri itarakoreshwa...\r\n\r\nImirima ya mbere yemerera guhitamo igishushanyo. Mu mishinga harimo gukora igishushanyo kimwe kizaba gitandukanye cyane n\'icyo dufite.\r\n\r\nN\'umurima wa kabiri `user_scroll`, uzakoreshwa kugirango wemerere guhindura urutonde rw\'ibikubiyemo. Ibi rimwe na rimwe bifite akamaro, nubwo kugabanya impapuro bizaguma by default.\r\n\r\n*Byose, ni inyandiko y\'ikizamini gusa, kugirango yuzuze ingingo imwe.*', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1, 0),
(6, 'Amakuru', 'information', 0, 'page', 0, 0, '2021-12-21 20:39:35', '2021-12-21 20:44:50', 1, 0, 1, 0x3132372e302e302e31, 0, 0, 0, 0, 0, 'Iki gice kirimo amakuru y\'ubufasha.  \r\n\r\n### Aho ushobora gukuramo ububiko bw\'urubuga?\r\n\r\nKura ububiko *agezweho kuri GitHub:* [github.com/AGEZWEHO/agezweho](https://github.com/AGEZWEHO/agezweho)\r\n\r\nIkode y\'urubuga ifite uruhushya [MIT](https://ru.wikipedia.org/wiki/%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F_MIT).  \r\n\r\n### Ibihe bintu bikoreshwa ku rubuga?\r\n\r\nMu gice cy\'inyandiko hari inyandiko: [Ibihe bintu bikoreshwa ku rubuga?](https://agezweho.com/ru/)\r\n\r\n### Aho ushobora gusoma neza inyandiko?\r\n\r\nMu gice cy\'inyandiko: [agezweho.com](https://agezweho.com)\r\n\r\n### Nigute ushobora gufasha urubuga?\r\n\r\n1. Gusaba inshuti zacu urubuga rwacu.\r\n2. Gusiga umurongo ku rubuga rwacu ku rubuga rwawe, urubuga, «Twitter», ku rupapuro rwawe ku mbuga nkoranyambaga cyangwa ku rubuga, aho bakuzi neza.\r\n3. Gukora inkunga y\'amafaranga uko bikunogeye. \r\n\r\n\r\nUburyo bwo kwishyura: *ЮMoney — * 4100140143763\r\n\r\n### Nigute ushobora kutwandikira?\r\n\r\nKu e-mail: *dev@agezweho.ru*\r\n\r\n*Dufite Discord:* [discord.gg/adJnPEGZZZ](https://discord.gg/adJnPEGZZZ)\r\n\r\n###  🙏  Murakoze\r\n\r\nMurakoze ku nkunga n\'ubufasha!', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, '', '', 0, 0),
(7, 'Politiki y\'ubwirinzi bw\'amakuru', 'privacy', 0, 'page', 0, 0, '2021-12-21 20:46:43', '2021-12-21 20:47:18', 1, 0, 1, 0x3132372e302e302e31, 0, 0, 0, 0, 0, 'Politiki y\'ubwirinzi bw\'amakuru\r\n\r\nHano twandika ku bwirinzi bw\'amakuru! \r\n\r\nIzi nyandiko zombi zifite umwanya ukomeye mu mihindagurikire kandi zigomba kuba ngombwa. Amazina y\'izo URL ntabwo ashobora guhinduka.', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, '', '', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `posts_signed`
--

CREATE TABLE `posts_signed` (
  `signed_id` int(11) NOT NULL,
  `signed_post_id` int(11) NOT NULL,
  `signed_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `posts_signed`
--

INSERT INTO `posts_signed` (`signed_id`, `signed_post_id`, `signed_user_id`) VALUES
(1, 4, 1),
(2, 5, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `posts_view`
--

CREATE TABLE `posts_view` (
  `view_id` int(11) NOT NULL,
  `view_post_id` int(11) DEFAULT NULL,
  `view_user_id` int(11) DEFAULT NULL,
  `view_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `posts_view`
--

INSERT INTO `posts_view` (`view_id`, `view_post_id`, `view_user_id`, `view_date`) VALUES
(1, 3, 1, '2024-03-03 02:01:04');

-- --------------------------------------------------------

--
-- Структура таблицы `replys`
--

CREATE TABLE `replys` (
  `reply_id` int(11) NOT NULL,
  `reply_parent_id` int(11) NOT NULL,
  `reply_item_id` int(11) NOT NULL,
  `reply_content` text NOT NULL,
  `reply_type` varchar(32) NOT NULL DEFAULT 'web' COMMENT 'web, soft...',
  `reply_user_id` int(11) NOT NULL,
  `reply_ip` varchar(64) NOT NULL,
  `reply_date` datetime NOT NULL DEFAULT current_timestamp(),
  `reply_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `reply_count` int(7) NOT NULL DEFAULT 0,
  `reply_votes` int(7) NOT NULL DEFAULT 0,
  `reply_published` tinyint(1) NOT NULL DEFAULT 1,
  `reply_is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Структура таблицы `search_logs`
--

CREATE TABLE `search_logs` (
  `id` int(11) NOT NULL,
  `request` text NOT NULL,
  `action_type` varchar(32) NOT NULL COMMENT 'Catalog, site...',
  `add_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `add_ip` varchar(45) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `count_results` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `stop_words`
--

CREATE TABLE `stop_words` (
  `stop_id` int(11) NOT NULL,
  `stop_word` varchar(50) DEFAULT NULL,
  `stop_add_uid` int(11) NOT NULL DEFAULT 0 COMMENT 'Кто добавил',
  `stop_space_id` int(11) NOT NULL DEFAULT 0 COMMENT '0 - глобально',
  `stop_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `activated` tinyint(1) DEFAULT 0,
  `limiting_mode` tinyint(1) DEFAULT 0,
  `reg_ip` varchar(45) DEFAULT NULL,
  `trust_level` int(11) NOT NULL COMMENT 'Уровень доверия. По умолчанию 1 (10 - админ)',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `invitation_available` int(11) NOT NULL DEFAULT 0,
  `invitation_id` int(11) NOT NULL DEFAULT 0,
  `template` varchar(12) NOT NULL DEFAULT 'default',
  `lang` varchar(20) NOT NULL DEFAULT 'ki',
  `scroll` tinyint(1) NOT NULL DEFAULT 0,
  `whisper` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'noavatar.png',
  `cover_art` varchar(255) NOT NULL DEFAULT 'cover_art.jpeg',
  `color` varchar(12) NOT NULL DEFAULT '#f56400',
  `about` varchar(250) DEFAULT NULL,
  `website` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `public_email` varchar(50) DEFAULT NULL,
  `github` varchar(50) DEFAULT NULL,
  `skype` varchar(50) DEFAULT NULL,
  `twitter` varchar(50) DEFAULT NULL,
  `telegram` varchar(50) DEFAULT NULL,
  `vk` varchar(50) DEFAULT NULL,
  `rating` int(11) DEFAULT 0,
  `my_post` int(11) DEFAULT 0 COMMENT 'Пост выведенный в профиль',
  `nsfw` tinyint(1) NOT NULL DEFAULT 0,
  `post_design` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'The appearance of the post in the feed: 0 - classic, 1 - card ...',
  `ban_list` tinyint(1) DEFAULT 0,
  `hits_count` int(11) DEFAULT 0,
  `up_count` int(11) DEFAULT 0,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

TRUNCATE TABLE `users`;

INSERT INTO `users` (`id`, `login`, `name`, `email`, `password`, `activated`, `limiting_mode`, `reg_ip`, `trust_level`, `created_at`, `updated_at`, `invitation_available`, `invitation_id`, `template`, `lang`, `scroll`, `whisper`, `avatar`, `cover_art`, `color`, `about`, `website`, `location`, `public_email`, `github`, `skype`, `twitter`, `telegram`, `vk`, `rating`, `my_post`, `nsfw`, `post_design`, `ban_list`, `hits_count`, `up_count`, `is_deleted`) VALUES
(1, 'seo', 'Didier Iradukunda', 'ss@agezweho.com', '$2y$10$oR5VZ.zk7IN/og70gQq/f.0Sb.GQJ33VZHIES4pyIpU3W2vF6aiaW', 1, 0, '127.0.0.1', 10, '2021-03-08 21:37:04', '2021-03-08 21:37:04', 0, 0, 'default', 'ki', 0, '', 'img_1.jpg', 'cover_art.jpeg', '#f56400', 'Тестовый аккаунт', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'test', NULL, 'test@test.ru', '$2y$10$Iahcsh3ima0kGqgk6S/SSui5/ETU5bQueYROFhOsjUU/z1.xynR7W', 1, 0, '127.0.0.1', 2, '2021-04-30 07:42:52', '2021-04-30 07:42:52', 0, 0, 'default', 'ki', 0, '', 'noavatar.png', 'cover_art.jpeg', '#339900', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 1, 0, 0);

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Структура таблицы `users_action_logs`
--

CREATE TABLE `users_action_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User ID',
  `user_login` varchar(50) NOT NULL COMMENT 'User login',
  `id_content` int(11) NOT NULL COMMENT 'Content ID',
  `action_type` varchar(32) NOT NULL,
  `action_name` varchar(124) NOT NULL COMMENT 'Action name',
  `url_content` varchar(250) NOT NULL COMMENT 'URL content',
  `add_date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date added'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_activate`
--

CREATE TABLE `users_activate` (
  `activate_id` int(11) NOT NULL,
  `activate_date` datetime NOT NULL,
  `activate_user_id` int(11) NOT NULL,
  `activate_code` varchar(50) NOT NULL,
  `activate_flag` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_agent_logs`
--

CREATE TABLE `users_agent_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_browser` varchar(64) NOT NULL,
  `user_os` varchar(64) NOT NULL,
  `user_ip` varchar(64) NOT NULL,
  `device_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Дамп данных таблицы `users_agent_logs`
--

INSERT INTO `users_agent_logs` (`id`, `add_date`, `user_id`, `user_browser`, `user_os`, `user_ip`, `device_id`) VALUES
(1, '2021-09-19 22:09:38', 1, 'Firefox 92.0', 'Windows', '127.0.0.1', NULL),
(2, '2021-09-19 22:57:57', 2, 'Chrome 93.0.4577.82', 'Windows', '127.0.0.1', NULL),
(3, '2021-10-17 16:43:05', 1, 'Firefox 93.0', 'Windows', '127.0.0.1', NULL),
(4, '2021-10-25 21:24:03', 1, 'Firefox 93.0', 'Windows', '127.0.0.1', NULL),
(5, '2021-11-04 08:01:34', 1, 'Firefox 94.0', 'Windows', '127.0.0.1', NULL),
(6, '2021-12-05 00:38:15', 1, 'Firefox 94.0', 'Windows', '127.0.0.1', NULL),
(7, '2021-12-05 10:51:36', 1, 'Firefox 94.0', 'Windows', '127.0.0.1', NULL),
(8, '2021-12-07 06:25:29', 1, 'Firefox 95.0', 'Windows', '127.0.0.1', NULL),
(9, '2021-12-07 07:15:41', 2, 'Firefox 95.0', 'Windows', '127.0.0.1', NULL),
(10, '2021-12-07 13:40:13', 1, 'Firefox 95.0', 'Windows', '127.0.0.1', NULL),
(11, '2021-12-07 13:49:18', 2, 'Firefox 95.0', 'Windows', '127.0.0.1', NULL),
(12, '2021-12-21 23:03:39', 1, 'Firefox 95.0', 'Windows', '127.0.0.1', NULL),
(13, '2021-12-21 23:08:44', 1, 'Firefox 95.0', 'Windows', '127.0.0.1', NULL),
(14, '2021-12-21 23:09:08', 1, 'Firefox 95.0', 'Windows', '127.0.0.1', NULL),
(15, '2022-02-09 17:50:18', 1, 'Firefox 96.0', 'Windows', '127.0.0.1', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users_auth_tokens`
--

CREATE TABLE `users_auth_tokens` (
  `auth_id` int(11) NOT NULL,
  `auth_user_id` int(11) NOT NULL,
  `auth_selector` varchar(255) NOT NULL,
  `auth_hashedvalidator` varchar(255) NOT NULL,
  `auth_expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_banlist`
--

CREATE TABLE `users_banlist` (
  `banlist_id` int(11) NOT NULL,
  `banlist_user_id` int(11) NOT NULL,
  `banlist_ip` varchar(45) NOT NULL,
  `banlist_bandate` timestamp NOT NULL DEFAULT current_timestamp(),
  `banlist_int_num` int(11) NOT NULL,
  `banlist_int_period` varchar(20) NOT NULL,
  `banlist_status` tinyint(1) NOT NULL DEFAULT 1,
  `banlist_autodelete` tinyint(1) NOT NULL DEFAULT 0,
  `banlist_cause` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_email_activate`
--

CREATE TABLE `users_email_activate` (
  `id` int(11) NOT NULL,
  `pubdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `email_code` varchar(50) NOT NULL,
  `email_activate_flag` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_email_story`
--

CREATE TABLE `users_email_story` (
  `id` int(11) NOT NULL,
  `pubdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `email_code` varchar(50) NOT NULL,
  `email_activate_flag` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_ignored`
--

CREATE TABLE `users_ignored` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ignored_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_preferences`
--

CREATE TABLE `users_preferences` (
  `user_id` int(11) NOT NULL,
  `facet_id` int(11) DEFAULT NULL,
  `type` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_setting`
--

CREATE TABLE `users_setting` (
  `setting_id` int(11) NOT NULL,
  `setting_user_id` int(11) UNSIGNED NOT NULL,
  `setting_email_pm` tinyint(1) UNSIGNED DEFAULT NULL COMMENT 'Написал ПМ',
  `setting_email_appealed` tinyint(1) UNSIGNED DEFAULT NULL COMMENT 'Обратился @',
  `setting_email_post` tinyint(1) UNSIGNED DEFAULT NULL COMMENT 'Написал пост',
  `setting_email_answer` tinyint(1) UNSIGNED DEFAULT NULL COMMENT 'Ответил'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `votes_comment`
--

CREATE TABLE `votes_comment` (
  `votes_comment_id` int(11) NOT NULL,
  `votes_comment_item_id` int(11) NOT NULL,
  `votes_comment_points` int(11) NOT NULL,
  `votes_comment_ip` varchar(45) NOT NULL,
  `votes_comment_user_id` int(11) NOT NULL DEFAULT 1,
  `votes_comment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `votes_item`
--

CREATE TABLE `votes_item` (
  `votes_item_id` int(11) NOT NULL,
  `votes_item_item_id` int(11) NOT NULL,
  `votes_item_points` int(11) NOT NULL,
  `votes_item_ip` varchar(45) NOT NULL,
  `votes_item_user_id` int(11) NOT NULL DEFAULT 1,
  `votes_item_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `votes_post`
--

CREATE TABLE `votes_post` (
  `votes_post_id` int(11) NOT NULL,
  `votes_post_item_id` int(11) NOT NULL,
  `votes_post_points` int(11) NOT NULL,
  `votes_post_ip` varchar(45) NOT NULL,
  `votes_post_user_id` int(11) NOT NULL DEFAULT 1,
  `votes_post_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `votes_post`
--

INSERT INTO `votes_post` (`votes_post_id`, `votes_post_item_id`, `votes_post_points`, `votes_post_ip`, `votes_post_user_id`, `votes_post_date`) VALUES
(1, 2, 1, '127.0.0.1', 1, '2021-08-16 07:29:32');

-- --------------------------------------------------------

--
-- Структура таблицы `votes_reply`
--

CREATE TABLE `votes_reply` (
  `votes_reply_id` int(11) NOT NULL,
  `votes_reply_item_id` int(11) NOT NULL,
  `votes_reply_points` int(11) NOT NULL,
  `votes_reply_ip` varchar(45) NOT NULL,
  `votes_reply_user_id` int(11) NOT NULL DEFAULT 1,
  `votes_reply_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action_type` (`action_type`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `content_id` (`content_id`);

--
-- Индексы таблицы `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`badge_id`);

--
-- Индексы таблицы `badges_user`
--
ALTER TABLE `badges_user`
  ADD PRIMARY KEY (`bu_id`),
  ADD KEY `bu_badge_id` (`bu_badge_id`),
  ADD KEY `bu_user_id` (`bu_user_id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `answer_link_id_2` (`comment_post_id`,`comment_date`),
  ADD KEY `answer_date` (`comment_date`),
  ADD KEY `answer_user_id` (`comment_user_id`,`comment_date`),
  ADD KEY `answer_post_id` (`comment_post_id`);

--
-- Индексы таблицы `facets`
--
ALTER TABLE `facets`
  ADD PRIMARY KEY (`facet_id`),
  ADD UNIQUE KEY `unique_index` (`facet_slug`,`facet_type`),
  ADD KEY `facet_slug` (`facet_slug`),
  ADD KEY `facet_merged_id` (`facet_merged_id`),
  ADD KEY `facet_type` (`facet_type`);

--
-- Индексы таблицы `facets_items_relation`
--
ALTER TABLE `facets_items_relation`
  ADD KEY `relation_facet_id` (`relation_facet_id`) USING BTREE,
  ADD KEY `relation_item_id` (`relation_item_id`) USING BTREE;

--
-- Индексы таблицы `facets_matching`
--
ALTER TABLE `facets_matching`
  ADD UNIQUE KEY `matching_parent_id` (`matching_parent_id`,`matching_chaid_id`);

--
-- Индексы таблицы `facets_merge`
--
ALTER TABLE `facets_merge`
  ADD PRIMARY KEY (`merge_id`),
  ADD KEY `merge_source_id` (`merge_source_id`),
  ADD KEY `merge_target_id` (`merge_target_id`),
  ADD KEY `merge_user_id` (`merge_user_id`);

--
-- Индексы таблицы `facets_posts_relation`
--
ALTER TABLE `facets_posts_relation`
  ADD KEY `relation_facet_id` (`relation_facet_id`),
  ADD KEY `relation_content_id` (`relation_post_id`);

--
-- Индексы таблицы `facets_relation`
--
ALTER TABLE `facets_relation`
  ADD UNIQUE KEY `facet_parent_id` (`facet_parent_id`,`facet_chaid_id`);

--
-- Индексы таблицы `facets_signed`
--
ALTER TABLE `facets_signed`
  ADD PRIMARY KEY (`signed_id`);

--
-- Индексы таблицы `facets_types`
--
ALTER TABLE `facets_types`
  ADD PRIMARY KEY (`type_id`),
  ADD UNIQUE KEY `title_UNIQUE` (`type_code`);

--
-- Индексы таблицы `facets_users_team`
--
ALTER TABLE `facets_users_team`
  ADD PRIMARY KEY (`team_id`),
  ADD KEY `team_facet_id` (`team_facet_id`),
  ADD KEY `team_user_id` (`team_user_id`);

--
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favorite_user_id` (`id`),
  ADD KEY `favorite_id` (`tid`);

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `file_user_id` (`file_user_id`);

--
-- Индексы таблицы `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `folders_relation`
--
ALTER TABLE `folders_relation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `action_type` (`action_type`,`tid`,`user_id`);

--
-- Индексы таблицы `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`invitation_id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `invitation_code` (`invitation_code`),
  ADD KEY `active_time` (`active_time`),
  ADD KEY `active_ip` (`active_ip`),
  ADD KEY `active_status` (`active_status`);

--
-- Индексы таблицы `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);
ALTER TABLE `items` ADD FULLTEXT KEY `item_title` (`item_title`,`item_content`,`item_domain`);

--
-- Индексы таблицы `items_signed`
--
ALTER TABLE `items_signed`
  ADD PRIMARY KEY (`signed_id`);

--
-- Индексы таблицы `items_status`
--
ALTER TABLE `items_status`
  ADD PRIMARY KEY (`status_id`),
  ADD KEY `status_item_id` (`status_item_id`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `message_dialog_id` (`message_dialog_id`),
  ADD KEY `message_sender_id` (`message_sender_id`),
  ADD KEY `message_add_time` (`message_date`),
  ADD KEY `message_sender_remove` (`message_sender_remove`),
  ADD KEY `message_recipient_remove` (`message_recipient_remove`),
  ADD KEY `message_sender_receipt` (`message_receipt`);

--
-- Индексы таблицы `messages_dialog`
--
ALTER TABLE `messages_dialog`
  ADD PRIMARY KEY (`dialog_id`),
  ADD KEY `dialog_recipient_id` (`dialog_recipient_id`),
  ADD KEY `dialog_sender_id` (`dialog_sender_id`),
  ADD KEY `dialog_update_time` (`dialog_update_time`),
  ADD KEY `dialog_add_time` (`dialog_add_time`);

--
-- Индексы таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipient_read_flag` (`recipient_id`,`read_flag`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `action_type` (`action_type`),
  ADD KEY `add_time` (`add_time`);

--
-- Индексы таблицы `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`poll_id`);

--
-- Индексы таблицы `polls_answers`
--
ALTER TABLE `polls_answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `answer_question_id` (`answer_question_id`);

--
-- Индексы таблицы `polls_votes`
--
ALTER TABLE `polls_votes`
  ADD PRIMARY KEY (`vote_id`),
  ADD KEY `vote_question_id` (`vote_question_id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `post_date` (`post_date`),
  ADD KEY `post_user_id` (`post_user_id`,`post_date`);
ALTER TABLE `posts` ADD FULLTEXT KEY `post_title` (`post_title`,`post_content`);

--
-- Индексы таблицы `posts_signed`
--
ALTER TABLE `posts_signed`
  ADD PRIMARY KEY (`signed_id`);

--
-- Индексы таблицы `posts_view`
--
ALTER TABLE `posts_view`
  ADD PRIMARY KEY (`view_id`),
  ADD KEY `view_user_id` (`view_user_id`),
  ADD KEY `view_post_id` (`view_post_id`);

--
-- Индексы таблицы `replys`
--
ALTER TABLE `replys`
  ADD PRIMARY KEY (`reply_id`);

--
-- Индексы таблицы `search_logs`
--
ALTER TABLE `search_logs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `stop_words`
--
ALTER TABLE `stop_words`
  ADD PRIMARY KEY (`stop_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reg_ip` (`reg_ip`);

--
-- Индексы таблицы `users_action_logs`
--
ALTER TABLE `users_action_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) COMMENT 'uid';

--
-- Индексы таблицы `users_activate`
--
ALTER TABLE `users_activate`
  ADD PRIMARY KEY (`activate_id`);

--
-- Индексы таблицы `users_agent_logs`
--
ALTER TABLE `users_agent_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_ip` (`user_ip`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users_auth_tokens`
--
ALTER TABLE `users_auth_tokens`
  ADD PRIMARY KEY (`auth_id`);

--
-- Индексы таблицы `users_banlist`
--
ALTER TABLE `users_banlist`
  ADD PRIMARY KEY (`banlist_id`),
  ADD KEY `banlist_ip` (`banlist_ip`),
  ADD KEY `banlist_user_id` (`banlist_user_id`);

--
-- Индексы таблицы `users_email_activate`
--
ALTER TABLE `users_email_activate`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_email_story`
--
ALTER TABLE `users_email_story`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_ignored`
--
ALTER TABLE `users_ignored`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ignored_id` (`ignored_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users_preferences`
--
ALTER TABLE `users_preferences`
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users_setting`
--
ALTER TABLE `users_setting`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `setting_user_id` (`setting_user_id`);

--
-- Индексы таблицы `votes_comment`
--
ALTER TABLE `votes_comment`
  ADD PRIMARY KEY (`votes_comment_id`),
  ADD KEY `votes_answer_item_id` (`votes_comment_item_id`,`votes_comment_user_id`),
  ADD KEY `votes_answer_ip` (`votes_comment_item_id`,`votes_comment_ip`),
  ADD KEY `votes_answer_user_id` (`votes_comment_user_id`);

--
-- Индексы таблицы `votes_item`
--
ALTER TABLE `votes_item`
  ADD PRIMARY KEY (`votes_item_id`),
  ADD KEY `votes_item_item_id` (`votes_item_item_id`,`votes_item_user_id`) USING BTREE,
  ADD KEY `votes_item_user_id` (`votes_item_user_id`) USING BTREE,
  ADD KEY `votes_item_ip` (`votes_item_item_id`,`votes_item_ip`) USING BTREE;

--
-- Индексы таблицы `votes_post`
--
ALTER TABLE `votes_post`
  ADD PRIMARY KEY (`votes_post_id`),
  ADD KEY `votes_post_item_id` (`votes_post_item_id`,`votes_post_user_id`),
  ADD KEY `votes_post_ip` (`votes_post_item_id`,`votes_post_ip`),
  ADD KEY `votes_post_user_id` (`votes_post_user_id`);

--
-- Индексы таблицы `votes_reply`
--
ALTER TABLE `votes_reply`
  ADD PRIMARY KEY (`votes_reply_id`),
  ADD KEY `votes_reply_item_id` (`votes_reply_item_id`,`votes_reply_user_id`) USING BTREE,
  ADD KEY `votes_reply_user_id` (`votes_reply_user_id`) USING BTREE,
  ADD KEY `votes_reply_ip` (`votes_reply_item_id`,`votes_reply_ip`) USING BTREE;

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `audits`
--
ALTER TABLE `audits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `badges`
--
ALTER TABLE `badges`
  MODIFY `badge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `badges_user`
--
ALTER TABLE `badges_user`
  MODIFY `bu_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `facets`
--
ALTER TABLE `facets`
  MODIFY `facet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `facets_merge`
--
ALTER TABLE `facets_merge`
  MODIFY `merge_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `facets_signed`
--
ALTER TABLE `facets_signed`
  MODIFY `signed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `facets_types`
--
ALTER TABLE `facets_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `facets_users_team`
--
ALTER TABLE `facets_users_team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `folders_relation`
--
ALTER TABLE `folders_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `invitations`
--
ALTER TABLE `invitations`
  MODIFY `invitation_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `items_signed`
--
ALTER TABLE `items_signed`
  MODIFY `signed_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `items_status`
--
ALTER TABLE `items_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `messages_dialog`
--
ALTER TABLE `messages_dialog`
  MODIFY `dialog_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `polls`
--
ALTER TABLE `polls`
  MODIFY `poll_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `polls_answers`
--
ALTER TABLE `polls_answers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `polls_votes`
--
ALTER TABLE `polls_votes`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `posts_signed`
--
ALTER TABLE `posts_signed`
  MODIFY `signed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `posts_view`
--
ALTER TABLE `posts_view`
  MODIFY `view_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `replys`
--
ALTER TABLE `replys`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `search_logs`
--
ALTER TABLE `search_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `stop_words`
--
ALTER TABLE `stop_words`
  MODIFY `stop_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users_action_logs`
--
ALTER TABLE `users_action_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_activate`
--
ALTER TABLE `users_activate`
  MODIFY `activate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_agent_logs`
--
ALTER TABLE `users_agent_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `users_auth_tokens`
--
ALTER TABLE `users_auth_tokens`
  MODIFY `auth_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_banlist`
--
ALTER TABLE `users_banlist`
  MODIFY `banlist_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_email_activate`
--
ALTER TABLE `users_email_activate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_email_story`
--
ALTER TABLE `users_email_story`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_ignored`
--
ALTER TABLE `users_ignored`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_setting`
--
ALTER TABLE `users_setting`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `votes_comment`
--
ALTER TABLE `votes_comment`
  MODIFY `votes_comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `votes_item`
--
ALTER TABLE `votes_item`
  MODIFY `votes_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `votes_post`
--
ALTER TABLE `votes_post`
  MODIFY `votes_post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `votes_reply`
--
ALTER TABLE `votes_reply`
  MODIFY `votes_reply_id` int(11) NOT NULL AUTO_INCREMENT;