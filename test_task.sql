-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Фев 12 2019 г., 00:16
-- Версия сервера: 5.7.23
-- Версия PHP: 7.1.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test_task`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `phone_number` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `city` varchar(256) NOT NULL,
  `summ` double NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `first_name`, `last_name`, `phone_number`, `email`, `city`, `summ`, `created_at`) VALUES
(7, 'Валентин', 'Прядко', '+38(096)754-6821', 'valik_ne_skazhu@ukr.net', 'Зеленодольськ', 123.435, '2019-02-10 16:35:48'),
(8, 'Марина', 'Укнгло', '+38(096)754-6821', 'bla2@bla.com', 'Kyiv', 345, '2019-02-10 19:45:29'),
(9, 'Валентин', 'Прядко', '+38(096)754-6821', 'valik_ne_skazhu@ukr.net', 'Зеленодольськ', 800, '2019-02-10 19:50:27'),
(10, 'Юлия ', 'Коломойцева', '+38(097)565-3443', 'blablabla@bla.com', 'Астана', 1232.12, '2019-02-12 10:10:00');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
