<?php

$sql[_DB_PREFIX_.'sellbox_cat_list'] .= '
ALTER TABLE `'._DB_PREFIX_.'sellbox_cat_list`  DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO `'._DB_PREFIX_.'sellbox_cat_list`
(cat_id, cat_parent_id, cat_name, field_list)
VALUES
(102, NULL, "Motoryzacja", ""),
(103, 102, "Samochody osobowe", "143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(112, 111, "Mieszkania", "163, 165, 166, 206, 211"),
(122, 121, "Meble", "205"),
(134, 133, "Telefony komórkowe", "205"),
(155, 144, "Praca fizyczna", "176, 177"),
(160, 159, "Ciągniki", "143, 146, 201"),
(170, 169, "Ubrania", "198, 199, 205"),
(181, 180, "Zabawki", "205"),
(189, 188, "Psy", "205"),
(198, 196, "Rowery", "205"),
(205, 204, "Książki", "205"),
(214, 213, "Muzycy", "205"),
(233, 232, "Kosmetyki", "205"),
(243, 240, "Oferty biur podróży ", "205"),
(249, 247, "Oddam za darmo", "205"),
(258, 103, "Alfa Romeo", "97, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(300, 104, "Choper - Cruiser", "138, 143, 150, 152, 153, 210"),
(309, 105, "Autobusy", "143, 144, 145, 146, 147, 148, 209"),
(314, 106, "Kempingi", "143"),
(318, 107, "Osobowe", "154, 205"),
(322, 109, "Opony", "156, 157, 158, 159, 160, 205"),
(326, 112, "Wynajem", "163, 165, 166, 206, 211"),
(330, 113, "Wynajem", "167, 206, 211"),
(333, 114, "Wynajem", "168, 206, 212"),
(336, 115, "Wynajem", "170, 206"),
(339, 116, "Wynajem", "170, 206"),
(342, 119, "Wynajem", "206"),
(345, 120, "Wynajem", "206"),
(348, 122, "Biurka", "205"),
(357, 134, "HTC", "179, 205"),
(368, 136, "Drukarki i skanery", "190, 205"),
(377, 161, "Agregaty", "143"),
(386, 166, "Bydło", "143"),
(392, 189, "Psy bez rodowodu", "143"),
(422, 190, "Koty bez rodowodu", "143"),
(436, 191, "Akcesoria akwariowe", "143"),
(440, 205, "Literatura", "205"),
(448, 211, "Angielski", "205"),
(462, 212, "Finansowe", "205"),
(471, 239, "Ekologiczna", "205"),
(104, 102, "Motocykle i skutery", "138, 143, 150, 152, 153, 210"),
(111, NULL, "Nieruchomości", "138, 143, 150, 152, 153, 210"),
(113, 111, "Domy", "167, 206, 211"),
(126, 121, "Dekoracje", "205"),
(137, 133, "Tablety", "205"),
(156, 144, "Gastronomia", "176, 177"),
(161, 159, "Maszyny rolnicze", "143"),
(171, 169, "Buty", "198, 200, 205"),
(185, 180, "Buciki", "202, 204, 205"),
(190, 188, "Koty", "202, 204, 205"),
(199, 196, "Fitness", "205"),
(206, 204, "Muzyka", "205"),
(215, 213, "Budowa i remont", "205"),
(234, 232, "Makijaż ", "205"),
(244, 240, "Noclegi wakacyjne", "205"),
(248, 247, "Poszukuję ", "205"),
(259, 103, "Audi", "98, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(301, 104, "Enduro - Funbike", "138, 143, 150, 152, 153, 210"),
(310, 105, "Dostawcze", "139, 143, 144, 145, 146, 147, 148, 209"),
(315, 106, "Przyczepy tow.", "143"),
(319, 107, "Auta na części", "154, 205"),
(323, 109, "Felgi", "156, 162, 205"),
(327, 112, "Sprzedaż", "163, 165, 166, 206, 211"),
(331, 113, "Sprzedaż", "167, 206, 211"),
(334, 114, "Sprzedaż", "168, 206, 212"),
(337, 115, "Sprzedaż", "170, 206"),
(340, 116, "Sprzedaż", "170, 206"),
(343, 119, "Sprzedaż", "206"),
(346, 120, "Sprzedaż", "206"),
(349, 122, "Fotele", "205"),
(358, 134, "Huawei", "180, 205"),
(369, 136, "Komputery PC", "191, 205"),
(378, 161, "Kombajny", "143"),
(387, 166, "Drób", "143"),
(393, 189, "Amstaff", "143"),
(423, 190, "Bengalski", "143"),
(437, 191, "Akwaria", "143"),
(441, 205, "Czasopisma", "205"),
(449, 211, "Biologia", "205"),
(463, 212, "Językowe", "205"),
(472, 239, "Produkty dla dzieci", "205"),
(105, 102, "Dostawcze i Ciężarowe", "143, 144, 145, 146, 147, 148, 209"),
(114, 111, "Działki", "168, 206, 212"),
(121, NULL, "Dla domu", "205"),
(128, 121, "Ogród", "205"),
(136, 133, "Komputery", "205"),
(145, 144, "Biurowa", "176, 177"),
(162, 159, "Przyczepy ", "143"),
(172, 169, "Bielizna", "198, 205"),
(182, 180, "Wózki dziecięce", "205"),
(191, 188, "Akwarystyka", "205"),
(200, 196, "Sporty zimowe", "205"),
(207, 204, "Filmy", "205"),
(216, 213, "Obsługa imprez", "205"),
(235, 232, "Manicure i pedicure", "205"),
(246, 240, "Kempingi", "205"),
(260, 103, "BMW", "99, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(302, 104, "Cross", "138, 143, 150, 152, 153, 210"),
(311, 105, "Ciężarowe", "140, 143, 144, 145, 146, 147, 148, 209"),
(316, 106, "Wózki widłowe", "143"),
(320, 107, "Ciężarowe", "154, 205"),
(324, 109, "Koła", "156, 205"),
(328, 112, "Zamiana", "163, 165, 166, 206, 211"),
(332, 113, "Zamiana", "167, 206, 211"),
(335, 114, "Zamiana", "168, 206, 212"),
(338, 115, "Zamiana", "170, 206"),
(341, 116, "Zamiana", "170, 206"),
(344, 119, "Zamiana", "206"),
(347, 120, "Zamiana", "206"),
(350, 122, "Komplety mebli", "205"),
(359, 134, "iPhone", "181, 205"),
(370, 136, "Laptopy", "192, 205"),
(379, 161, "Kosiarki", "143"),
(388, 166, "Kozy", "143"),
(394, 189, "Basset Hound", "143"),
(424, 190, "Brytyjski", "143"),
(438, 191, "Rośliny akwariowe", "143"),
(442, 205, "Dla dzieci", "205"),
(450, 211, "Chemia", "205"),
(464, 212, "Komputerowe", "205"),
(473, 239, "Pozostałe produkty", "205"),
(106, 102, "Pojazdy użytkowe", "143"),
(115, 111, "Biura i lokale", "170, 206"),
(124, 121, "Sprzęt AGD", "205"),
(133, NULL, "Elektronika", "205"),
(138, 133, "Telewizory", "205"),
(153, 144, "Ochrona", "176, 177"),
(163, 159, "Części do maszyn ", "205"),
(173, 169, "Suknie ślubne", "205"),
(184, 180, "Ubranka dla dzieci", "202, 203, 205"),
(193, 188, "Akcesoria dla zwierząt", "205"),
(201, 196, "Wędkarstwo", "205"),
(217, 213, "Serwis AGD", "205"),
(236, 232, "Perfumy i wody", "205"),
(245, 240, "Hotele", "205"),
(261, 103, "Cadillac", "100, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(303, 104, "Motorower", "138, 143, 150, 152, 153, 210"),
(312, 105, "Naczepy", "143, 144, 145, 146, 147, 148"),
(317, 106, "Pozostałe użytkowe", "143"),
(321, 107, "Pozostałe części", "154, 205"),
(325, 109, "Pozostałe opony i felgi", "156, 205"),
(351, 122, "Łóżka i materace", "205"),
(360, 134, "LG", "182, 205"),
(371, 136, "Monitory", "193, 205"),
(380, 161, "Opryskiwacze", "143"),
(389, 166, "Owce", "143"),
(395, 189, "Beagle", "143"),
(425, 190, "Burmański", "143"),
(439, 191, "Zwierzęta akwariowe", "143"),
(443, 205, "Komiksy", "205"),
(451, 211, "Fizyka", "205"),
(465, 212, "Kosmetyczne", "205"),
(107, 102, "Części samochodowe", "154, 205"),
(116, 111, "Garaże i parkingi", "154, 205"),
(125, 121, "Akcesoria kuchenne", "205"),
(142, 133, "Sprzęt audio", "205"),
(144, NULL, "Praca", "176, 177"),
(150, 144, "Informatyka", "176, 177"),
(164, 159, "Opony do maszyn", "205"),
(174, 169, "Odzież ciążowa", "199, 205"),
(183, 180, "Foteliki / Nosidełka", "205"),
(192, 188, "Usługi dla zwierząt", "205"),
(197, 196, "Kolekcje", "205"),
(209, 204, "Sprzęt muzyczny", "205"),
(218, 213, "Sprzątanie", "205"),
(237, 232, "Zdrowie, medycyna", "205"),
(241, 240, "Agroturystyka", "205"),
(262, 103, "Chevrolet", "101, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(304, 104, "Quad - ATV", "138, 143, 150, 152, 153, 210"),
(313, 105, "Pozostałe dostawcze", "143, 144, 145, 146, 147, 148"),
(352, 122, "Regały", "205"),
(361, 134, "Microsoft", "183, 205"),
(372, 136, "Myszki i klawiatury", "194, 205"),
(381, 161, "Prasy", "143"),
(390, 166, "Trzoda", "143"),
(396, 189, "Bernardyn", "143"),
(426, 190, "Devon Rex", "143"),
(444, 205, "Książki naukowe", "205"),
(452, 211, "Francuski", "205"),
(466, 212, "Prawo jazdy", "205"),
(108, 102, "Części motocyklowe", "142, 205"),
(117, 111, "Noclegi", "171, 172"),
(129, 121, "Narzędzia", "205"),
(139, 133, "Gry i konsole", "205"),
(152, 144, "Obsługa klienta", "176, 177"),
(159, NULL, "Rolnictwo", "176, 177"),
(165, 159, "Produkty rolne", "176, 177"),
(176, 169, "Biżuteria", "198, 205"),
(186, 180, "Meble dla dzieci", "205"),
(194, 188, "Zaginione / znalezione", "205"),
(202, 196, "Turystyka", "205"),
(211, 204, "Korepetycje", "205"),
(219, 213, "Tłumaczenia", "205"),
(238, 232, "Pozostałe uroda", "205"),
(242, 240, "Atrakcje turystyczne", "205"),
(263, 103, "Chrysler", "102, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(305, 104, "Skuter", "138, 143, 150, 152, 153, 210"),
(353, 122, "Sofy i kanapy", "205"),
(362, 134, "Motorola", "184, 205"),
(373, 136, "Oprogramowanie", "195, 205"),
(382, 161, "Pługi", "143"),
(391, 166, "Pozostałe zwierzęta rolne", "143"),
(397, 189, "Bermeński pies pasterny", "143"),
(427, 190, "Maine Coon", "143"),
(445, 205, "Podręczniki szkolne", "205"),
(453, 211, "Historia", "205"),
(467, 212, "Tańca", "205"),
(109, 102, "Opony i felgi", "156, 205"),
(118, 111, "Stancje i pokoje", "166, 173, 174, 206"),
(131, 121, "Ogrzewanie", "205"),
(141, 133, "Fotografia", "205"),
(151, 144, "Kierowca / Kurier", "176, 177"),
(166, 159, "Giełda zwierząt", "176, 177"),
(169, NULL, "Moda", "205"),
(177, 169, "Zegarki", "198, 205"),
(187, 180, "Pozostałe dla dzieci", "205"),
(195, 188, "Pozostałe zwierzęta", "205"),
(203, 196, "Społeczność", "205"),
(212, 204, "Kursy i warsztaty", "205"),
(220, 213, "Finansowe", "205"),
(257, 240, "Pozostałe wakacje", "205"),
(264, 103, "Citroen", "103, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(306, 104, "Sportowy", "138, 143, 150, 152, 153, 210"),
(354, 122, "Stoły i krzesła", "205"),
(363, 134, "Nokia", "185, 205"),
(374, 136, "Routery i modemy", "196, 205"),
(398, 189, "Bokser", "196, 205"),
(428, 190, "Norweski", "196, 205"),
(446, 205, "Poradniki i albumy", "205"),
(454, 211, "Hiszpański", "205"),
(468, 212, "Zawodowe", "205"),
(110, 102, "Pozostałe motoryzacja", "205"),
(119, 111, "Hale i magazyny ", "206"),
(130, 121, "Materiały budowlane", "205"),
(135, 133, "Akcesoria", "205"),
(146, 144, "Chałupnictwo", "176, 177"),
(167, 159, "Ryneczek", "176, 177"),
(178, 169, "Kosmetyki i perfumy", "198, 205"),
(180, NULL, "Dla dzieci", "205"),
(210, 204, "Pozostałe hobby", "205"),
(221, 213, "Informatyczne", "205"),
(256, 196, "Pozostałe sport", "205"),
(265, 103, "Dacia", "104, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(307, 104, "Turystyczny", "138, 143, 150, 152, 153, 210"),
(355, 122, "Szafy i komody", "205"),
(364, 134, "Samsung", "186, 205"),
(375, 136, "Akcesoria i części", "197, 205"),
(384, 161, "Siewniki", "143"),
(399, 189, "Buldog", "143"),
(429, 190, "Perski", "143"),
(447, 205, "Pozostałe książki", "205"),
(455, 211, "Matematyka", "205"),
(469, 212, "Kursy inne", "205"),
(120, 111, "Pozostałe nieruchomości", "205"),
(123, 121, "Meble na wymiar", "205"),
(140, 133, "Sprzęt DVD / BlueRay", "205"),
(147, 144, "Edukacja", "176, 177"),
(168, 159, "Pozostałe rolnictwo", "176, 177"),
(175, 169, "Dodatki", "198, 205"),
(188, NULL, "Zwierzęta", "198, 205"),
(222, 213, "Kosmetyczne", "198, 205"),
(266, 103, "Daewoo", "105, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(308, 104, "Pozostałe motocykle", "138, 143, 150, 152, 153"),
(356, 122, "Pozostałe meble", "205"),
(365, 134, "Sony", "187, 205"),
(376, 136, "Pozostałe komputery", "205"),
(385, 161, "Pozostałe maszyny rolnicze", "143"),
(400, 189, "Cane Croso", "143"),
(430, 190, "Ragdoll", "143"),
(456, 211, "Muzyka", "143"),
(132, 121, "Pozostałe dla domu", "205"),
(143, 133, "Pozostałe elektronika", "205"),
(148, 144, "Fryzjer / Kosmetyczka", "176, 177"),
(179, 169, "Pozostałe moda", "205"),
(196, NULL, "Sport", "205"),
(223, 213, "Motoryzacyjne", "205"),
(267, 103, "Daihatsu", "106, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(366, 134, "Sony Ericsson", "188, 205"),
(401, 189, "Chihuahua", "188, 205"),
(431, 190, "Rosyjski", "188, 205"),
(457, 211, "Niemiecki", "188, 205"),
(154, 144, "Opieka", "176, 177"),
(204, NULL, "Hobby", "176, 177"),
(224, 213, "Ogrodnicze", "176, 177"),
(268, 103, "Dodge", "107, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(402, 189, "Cocker spaniel", "107, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(432, 190, "Sfinks", "107, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(458, 211, "Polski", "107, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(470, 134, "Xiaomi", "189, 205"),
(149, 144, "Hostessa", "176, 177"),
(213, NULL, "Usługi", "176, 177"),
(225, 213, "Reklamowe", "176, 177"),
(269, 103, "Fiat", "108, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(367, 134, "Pozostałe telefony", "205"),
(403, 189, "Doberman", "205"),
(433, 190, "Syberyjski", "205"),
(459, 211, "Rosyjski", "205"),
(226, 213, "Transportowe", "205"),
(232, NULL, "Uroda", "205"),
(255, 144, "Sprzedaż", "176, 177"),
(270, 103, "Ford", "109, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(404, 189, "Dogo canario", "109, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(434, 190, "Syjamski", "109, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(460, 211, "Włoski", "109, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(157, 144, "Praca dodatkowa", "176, 177"),
(227, 213, "Biznesowe", "176, 177"),
(239, NULL, "Żywność", "176, 177"),
(271, 103, "Honda", "110, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(405, 189, "Golden Retriever", "110, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(435, 190, "Pozostałe rasy", "110, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(461, 211, "Inne", "110, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(158, 144, "Inne oferty pracy", "176, 177"),
(228, 213, "Wyposażenie firm", "176, 177"),
(240, NULL, "Wakacje", "176, 177"),
(272, 103, "Hyundai", "111, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(406, 189, "Grzywacz Chiński", "111, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(229, 213, "Ślub i wesele", "111, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(247, NULL, "Za darmo", "205"),
(273, 103, "Jaguar", "112, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(407, 189, "Husky", "112, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(230, 213, "Usługi zdrowotne", "112, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(274, 103, "Jeep", "113, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(408, 189, "Jack Russel terrier", "113, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(231, 213, "Pozostałe usługi", "113, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(275, 103, "Kia", "114, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(409, 189, "Jamnik", "114, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(276, 103, "Lancia", "115, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(410, 189, "Labrador", "115, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(277, 103, "Land Rover", "116, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(411, 189, "Maltańczyk", "116, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(278, 103, "Lexus", "117, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(412, 189, "Mops", "117, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(279, 103, "Mazda", "118, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(413, 189, "Owczarek", "118, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(280, 103, "Mercedes - Benz", "119, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(414, 189, "Pekińczyk", "119, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(281, 103, "Mini", "120, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(415, 189, "Pit Bull", "120, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(282, 103, "Mitsubishi", "121, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(416, 189, "Rotweiller", "121, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(283, 103, "Nissan", "122, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(417, 189, "Seter", "122, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(284, 103, "Opel", "123, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(418, 189, "Shih Tzu", "123, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(285, 103, "Peugeot", "124, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(419, 189, "Sznaucer", "124, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(286, 103, "Polonez", "125, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(420, 189, "York", "125, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(287, 103, "Porshe", "126, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(421, 189, "Pozostałe rasy", "126, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(288, 103, "Renault", "127, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(289, 103, "Rover", "128, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(290, 103, "Saab", "129, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(291, 103, "Seat", "130, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(292, 103, "Skoda", "131, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(293, 103, "Smart", "132, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(294, 103, "Subaru", "133, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(295, 103, "Suzuki", "134, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(296, 103, "Toyota", "135, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(297, 103, "Volkswagen", "136, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(298, 103, "Volvo", "137, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 209"),
(299, 103, "Pozostałe osobowe", "143, 144, 145, 146, 147, 148, 149, 150, 151, 152");';
