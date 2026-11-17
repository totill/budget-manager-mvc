<?php

declare(strict_types=1);

namespace App\Config;

class Categories
{
    public const INCOME = ['Wynagrodzenie', 'Odsetki bankowe', 'Sprzedaż na Allegro', 'Inne'];
    public const EXPENSE = ['Jedzenie', 'Mieszkanie', 'Transport', 'Telekomunikacja', 'Opieka zdrowotna', 'Ubranie', 'Higiena', 'Dzieci', 'Rozrywka', 'Wycieczka', 'Szkolenia', 'Książki', 'Oszczędności', 'Na złotą jesień, czyli emeryturę', 'Spłata długów', 'Darowizna', 'Inne wydatki'];
    public const PAYMENT = ['Gotówka', 'Karta kredytowa', 'Karta debetowa'];
}
