<?php
namespace App\Shop;

enum ShopType: string {
    case TAKEAWAY = 'Takeaway';
    case SHOP = 'Shop';

    case RESTAURANT = 'Restaurant';
}
