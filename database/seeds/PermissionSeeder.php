<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //`menu_id`, `name`, `display_name`, `screen_link`, `screen_order
        //
        $menu = new \App\Permission();
        $menu->menu_id = 1;
        $menu->name = "lawsuit";
        $menu->display_name = "عرض الملفات القضائية";
        $menu->screen_link = "lawsuit";
        $menu->screen_order = 1;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 1;
        $menu->name = "lawsuit";
        $menu->display_name = "فتح ملف جديد";
        $menu->screen_link = "lawsuit";
        $menu->screen_order = 2;
        $menu->save();
/********************************************/
        $menu = new \App\Permission();
        $menu->menu_id = 2;
        $menu->name = "task";
        $menu->display_name = "عرض المهام";
        $menu->screen_link = "task";
        $menu->screen_order = 1;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 2;
        $menu->name = "task";
        $menu->display_name = "إضافة مهمة جديدة";
        $menu->screen_link = "task/create";
        $menu->screen_order = 2;
        $menu->save();
  //*****************************************
        $menu = new \App\Permission();
        $menu->menu_id = 3;
        $menu->name = "employee";
        $menu->display_name = "عرض الموظفين";
        $menu->screen_link = "employee";
        $menu->screen_order = 1;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 3;
        $menu->name = "employee";
        $menu->display_name = "إضافة موظف جديد";
        $menu->screen_link = "employee/create";
        $menu->screen_order = 2;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 3;
        $menu->name = "employee";
        $menu->display_name = "الحضور والإنصراف";
        $menu->screen_link = "attendance";
        $menu->screen_order = 3;
        $menu->save();
        //***************************

        $menu = new \App\Permission();
        $menu->menu_id = 4;
        $menu->name = "rating";
        $menu->display_name = "معاير التقيم";
        $menu->screen_link = "setting/s6";
        $menu->screen_order = 1;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 4;
        $menu->name = "rating";
        $menu->display_name = "ادارة التقييمات";
        $menu->screen_link = "setting/s8";
        $menu->screen_order = 2;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 4;
        $menu->name = "rating";
        $menu->display_name = "تقييم الموظفين";
        $menu->screen_link = "rating";
        $menu->screen_order = 2;
        $menu->save();

        //********************************
        $menu = new \App\Permission();
        $menu->menu_id = 5;
        $menu->name = "role";
        $menu->display_name = "المستخدمين";
        $menu->screen_link = "user";
        $menu->screen_order = 1;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 5;
        $menu->name = "role";
        $menu->display_name = "فئات المستخدمين";
        $menu->screen_link = "role";
        $menu->screen_order = 2;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 5;
        $menu->name = "role";
        $menu->display_name = "انواع الصلاحيات";
        $menu->screen_link = "permission";
        $menu->screen_order = 3;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 5;
        $menu->name = "role";
        $menu->display_name = "صلاحيات فئات المستخدمين";
        $menu->screen_link = "role_permission";
        $menu->screen_order = 4;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 5;
        $menu->name = "role";
        $menu->display_name = "منح الصلاحيات للمستخدمين";
        $menu->screen_link = "user_permission";
        $menu->screen_order = 5;
        $menu->save();
        //****************************************
        $menu = new \App\Permission();
        $menu->menu_id = 6;
        $menu->name = "setting";
        $menu->display_name = " انواع الملف القضائي";
        $menu->screen_link = "setting/s1";
        $menu->screen_order = 1;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 6;
        $menu->name = "setting";
        $menu->display_name = " حالات الملف القضائي";
        $menu->screen_link = "setting/s11";
        $menu->screen_order = 2;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 6;
        $menu->name = "setting";
        $menu->display_name = " انواع الملفات ";
        $menu->screen_link = "setting/s2";
        $menu->screen_order = 3;
        $menu->save();


        $menu = new \App\Permission();
        $menu->menu_id = 6;
        $menu->name = "setting";
        $menu->display_name = " المحافظات ";
        $menu->screen_link = "setting/s3";
        $menu->screen_order = 4;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 6;
        $menu->name = "setting";
        $menu->display_name = " المحاكم ";
        $menu->screen_link = "setting/s4";
        $menu->screen_order = 5;
        $menu->save();

        $menu = new \App\Permission();
        $menu->menu_id = 6;
        $menu->name = "setting";
        $menu->display_name = " الوظائف ";
        $menu->screen_link = "setting/s5";
        $menu->screen_order = 6;
        $menu->save();
    }
}
