<?php

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = new \App\Menu();
        $menu->menu_name = "Patient";
        $menu->menu_order = 1;
        $menu->menu_icon = "icon-bulb";
        $menu->save();

        $menu = new \App\Menu();
        $menu->menu_name = "";
        $menu->menu_order = 2;
        $menu->menu_icon = "icon-briefcase";
        $menu->save();

        $menu = new \App\Menu();
        $menu->menu_name = "";
        $menu->menu_order = 3;
        $menu->menu_icon = "icon-diamond";
        $menu->save();

        $menu = new \App\Menu();
        $menu->menu_name = "";
        $menu->menu_order = 4;
        $menu->menu_icon = "icon-wallet";
        $menu->save();

        $menu = new \App\Menu();
        $menu->menu_name = "";
        $menu->menu_order = 5;
        $menu->menu_icon = "icon-bar-chart";
        $menu->save();

        $menu = new \App\Menu();
        $menu->menu_name = "";
        $menu->menu_order = 6;
        $menu->menu_icon = "fa fa-cogs";
        $menu->save();

        $menu = new \App\Menu();
        $menu->menu_name = "";
        $menu->menu_order = 7;
        $menu->menu_icon = "icon-layers";
        $menu->save();
    }
}
