CREATE TABLE IF NOT EXISTS #__mycomponent
(`id` int(10) unsigned NOT NULL auto_increment,
`name` varchar(255) NOT NULL default 'Бештау',
`opisanie` text,
`adres` varchar(255) NOT NULL default 'ул.Калинина №7',
`published` varchar(255) NOT NULL default '1',
`ordering` varchar(255) NOT NULL default '1',
PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO #__mycomponent
(`name`, `opisanie`, `adres`, `published`, `ordering`)
VALUES
('Бештау', 'Гостиница класса люкс со всеми удобствами', 'ул.Калинина №7', '1', '1');