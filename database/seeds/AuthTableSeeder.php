<?php

use Illuminate\Database\Seeder;

class AuthTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        if (\DB::table('auth')->count()) {
            return;
        }
        \DB::table('auth')->delete();

        \DB::table('auth')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => '博客管理',
                'parent_id' => 0,
                'auth_path' => '1_',
                'route_name' => 'blog.manage',
                'created_at' => 1602942798,
                'updated_at' => 1610520231,
            ),
            1 =>
            array (
                'id' => 2,
                'name' => '文章管理',
                'parent_id' => 1,
                'auth_path' => '1_2_',
                'route_name' => '',
                'created_at' => 1602942820,
                'updated_at' => 1610520231,
            ),
            2 =>
            array (
                'id' => 4,
                'name' => '标签管理',
                'parent_id' => 1,
                'auth_path' => '1_4_',
                'route_name' => '',
                'created_at' => 1602943099,
                'updated_at' => 1610520231,
            ),
            3 =>
            array (
                'id' => 5,
                'name' => '添加标签',
                'parent_id' => 4,
                'auth_path' => '1_4_5_',
                'route_name' => 'tag.store',
                'created_at' => 1602943678,
                'updated_at' => 1610520231,
            ),
            4 =>
            array (
                'id' => 6,
                'name' => '添加文章',
                'parent_id' => 2,
                'auth_path' => '1_2_6_',
                'route_name' => 'article.store',
                'created_at' => 1602993318,
                'updated_at' => 1610520231,
            ),
            5 =>
            array (
                'id' => 7,
                'name' => '修改文章',
                'parent_id' => 2,
                'auth_path' => '1_2_7_',
                'route_name' => 'article.update',
                'created_at' => 1602993378,
                'updated_at' => 1610520231,
            ),
            6 =>
            array (
                'id' => 8,
                'name' => '删除文章',
                'parent_id' => 2,
                'auth_path' => '1_2_8_',
                'route_name' => 'article.destroy',
                'created_at' => 1602996390,
                'updated_at' => 1610520231,
            ),
            7 =>
            array (
                'id' => 9,
                'name' => '查看列表',
                'parent_id' => 2,
                'auth_path' => '1_2_9_',
                'route_name' => 'article.index',
                'created_at' => 1602996981,
                'updated_at' => 1610520231,
            ),
            8 =>
            array (
                'id' => 10,
                'name' => '查看标签',
                'parent_id' => 4,
                'auth_path' => '1_4_10_',
                'route_name' => 'tag.index',
                'created_at' => 1602997018,
                'updated_at' => 1610520231,
            ),
            9 =>
            array (
                'id' => 11,
                'name' => '更新标签',
                'parent_id' => 4,
                'auth_path' => '1_4_11_',
                'route_name' => 'tag.update',
                'created_at' => 1602997056,
                'updated_at' => 1610520231,
            ),
            10 =>
            array (
                'id' => 12,
                'name' => '删除标签',
                'parent_id' => 4,
                'auth_path' => '1_4_12_',
                'route_name' => 'tag.destroy',
                'created_at' => 1602997084,
                'updated_at' => 1610520231,
            ),
            11 =>
            array (
                'id' => 13,
                'name' => '专题管理',
                'parent_id' => 1,
                'auth_path' => '1_13_',
                'route_name' => '',
                'created_at' => 1602997104,
                'updated_at' => 1610520231,
            ),
            12 =>
            array (
                'id' => 14,
                'name' => '查看专题',
                'parent_id' => 13,
                'auth_path' => '1_13_14_',
                'route_name' => 'topic.index',
                'created_at' => 1602997160,
                'updated_at' => 1610520231,
            ),
            13 =>
            array (
                'id' => 15,
                'name' => '创建专题',
                'parent_id' => 13,
                'auth_path' => '1_13_15_',
                'route_name' => 'topic.store',
                'created_at' => 1602997203,
                'updated_at' => 1610520231,
            ),
            14 =>
            array (
                'id' => 16,
                'name' => '修改专题',
                'parent_id' => 13,
                'auth_path' => '1_13_16_',
                'route_name' => 'topic.update',
                'created_at' => 1602997229,
                'updated_at' => 1610520231,
            ),
            15 =>
            array (
                'id' => 17,
                'name' => '删除专题',
                'parent_id' => 13,
                'auth_path' => '1_13_17_',
                'route_name' => 'topic.destroy',
                'created_at' => 1602997249,
                'updated_at' => 1610520231,
            ),
            16 =>
            array (
                'id' => 18,
                'name' => '工作台',
                'parent_id' => 0,
                'auth_path' => '18_',
                'route_name' => 'workbench',
                'created_at' => 1602997761,
                'updated_at' => 1610520231,
            ),
            17 =>
            array (
                'id' => 19,
                'name' => '仪表盘',
                'parent_id' => 18,
                'auth_path' => '18_19_',
                'route_name' => 'admin.dashboard',
                'created_at' => 1602997784,
                'updated_at' => 1610520231,
            ),
            18 =>
            array (
                'id' => 20,
                'name' => '授权管理',
                'parent_id' => 0,
                'auth_path' => '20_',
                'route_name' => 'authorize.manage',
                'created_at' => 1603032361,
                'updated_at' => 1610520231,
            ),
            19 =>
            array (
                'id' => 21,
                'name' => '权限管理',
                'parent_id' => 20,
                'auth_path' => '20_21_',
                'route_name' => 'auth.manage',
                'created_at' => 1603032380,
                'updated_at' => 1610520231,
            ),
            20 =>
            array (
                'id' => 22,
                'name' => '查看权限',
                'parent_id' => 21,
                'auth_path' => '20_21_22_',
                'route_name' => 'auth.index',
                'created_at' => 1603032398,
                'updated_at' => 1610520231,
            ),
            21 =>
            array (
                'id' => 23,
                'name' => '添加权限',
                'parent_id' => 21,
                'auth_path' => '20_21_23_',
                'route_name' => 'auth.store',
                'created_at' => 1603032416,
                'updated_at' => 1610520231,
            ),
            22 =>
            array (
                'id' => 24,
                'name' => '更改权限',
                'parent_id' => 21,
                'auth_path' => '20_21_24_',
                'route_name' => 'auth.update',
                'created_at' => 1603032430,
                'updated_at' => 1610520231,
            ),
            23 =>
            array (
                'id' => 25,
                'name' => '删除权限',
                'parent_id' => 21,
                'auth_path' => '20_21_25_',
                'route_name' => 'auth.destroy',
                'created_at' => 1603032444,
                'updated_at' => 1610520231,
            ),
            24 =>
            array (
                'id' => 26,
                'name' => '角色管理',
                'parent_id' => 20,
                'auth_path' => '20_26_',
                'route_name' => '',
                'created_at' => 1603032461,
                'updated_at' => 1610520231,
            ),
            25 =>
            array (
                'id' => 27,
                'name' => '查看角色',
                'parent_id' => 26,
                'auth_path' => '20_26_27_',
                'route_name' => 'role.index',
                'created_at' => 1603032477,
                'updated_at' => 1610520231,
            ),
            26 =>
            array (
                'id' => 28,
                'name' => '添加角色',
                'parent_id' => 26,
                'auth_path' => '20_26_28_',
                'route_name' => 'role.store',
                'created_at' => 1603032499,
                'updated_at' => 1610520231,
            ),
            27 =>
            array (
                'id' => 29,
                'name' => '修改角色',
                'parent_id' => 26,
                'auth_path' => '20_26_29_',
                'route_name' => 'role.update',
                'created_at' => 1603032513,
                'updated_at' => 1610520231,
            ),
            28 =>
            array (
                'id' => 30,
                'name' => '删除角色',
                'parent_id' => 26,
                'auth_path' => '20_26_30_',
                'route_name' => 'role.destroy',
                'created_at' => 1603032531,
                'updated_at' => 1610520231,
            ),
            29 =>
            array (
                'id' => 31,
                'name' => '管理员管理',
                'parent_id' => 20,
                'auth_path' => '20_31_',
                'route_name' => '',
                'created_at' => 1603032733,
                'updated_at' => 1610520231,
            ),
            30 =>
            array (
                'id' => 32,
                'name' => '添加管理员',
                'parent_id' => 31,
                'auth_path' => '20_31_32_',
                'route_name' => 'manager.create',
                'created_at' => 1603032753,
                'updated_at' => 1610520231,
            ),
            31 =>
            array (
                'id' => 33,
                'name' => '查看管理员',
                'parent_id' => 31,
                'auth_path' => '20_31_33_',
                'route_name' => 'manager.index',
                'created_at' => 1603032788,
                'updated_at' => 1610520231,
            ),
            32 =>
            array (
                'id' => 34,
                'name' => '删除管理员',
                'parent_id' => 31,
                'auth_path' => '20_31_34_',
                'route_name' => 'manager.destroy',
                'created_at' => 1603032814,
                'updated_at' => 1610520231,
            ),
            33 =>
            array (
                'id' => 35,
                'name' => '修改管理员',
                'parent_id' => 31,
                'auth_path' => '20_31_35_',
                'route_name' => 'manager.uddate',
                'created_at' => 1603032835,
                'updated_at' => 1610520231,
            ),
            34 =>
            array (
                'id' => 36,
                'name' => '画廊管理',
                'parent_id' => 0,
                'auth_path' => '36_',
                'route_name' => '',
                'created_at' => 1603032851,
                'updated_at' => 1610520231,
            ),
            35 =>
            array (
                'id' => 37,
                'name' => '查看图片',
                'parent_id' => 36,
                'auth_path' => '36_37_',
                'route_name' => 'gallery.index',
                'created_at' => 1603032870,
                'updated_at' => 1610520231,
            ),
            36 =>
            array (
                'id' => 38,
                'name' => '上传图片',
                'parent_id' => 36,
                'auth_path' => '36_38_',
                'route_name' => 'gallery.store',
                'created_at' => 1603032891,
                'updated_at' => 1610520231,
            ),
            37 =>
            array (
                'id' => 39,
                'name' => '友链管理',
                'parent_id' => 0,
                'auth_path' => '39_',
                'route_name' => '',
                'created_at' => 1603032906,
                'updated_at' => 1610520231,
            ),
            38 =>
            array (
                'id' => 40,
                'name' => '添加友链',
                'parent_id' => 39,
                'auth_path' => '39_40_',
                'route_name' => 'friend-link.store',
                'created_at' => 1603033034,
                'updated_at' => 1610520231,
            ),
            39 =>
            array (
                'id' => 41,
                'name' => '查看友链',
                'parent_id' => 39,
                'auth_path' => '39_41_',
                'route_name' => 'friend-link.index',
                'created_at' => 1603033052,
                'updated_at' => 1610520231,
            ),
            40 =>
            array (
                'id' => 42,
                'name' => '修改友链',
                'parent_id' => 39,
                'auth_path' => '39_42_',
                'route_name' => 'friend-link.update',
                'created_at' => 1603033073,
                'updated_at' => 1610520231,
            ),
            41 =>
            array (
                'id' => 43,
                'name' => '删除友链',
                'parent_id' => 39,
                'auth_path' => '39_43_',
                'route_name' => 'friend-link.destroy',
                'created_at' => 1603033087,
                'updated_at' => 1610520231,
            ),
            42 =>
            array (
                'id' => 44,
                'name' => '敏感词汇管理',
                'parent_id' => 0,
                'auth_path' => '44_',
                'route_name' => 'sensitive-word.manage',
                'created_at' => 1603033105,
                'updated_at' => 1610520231,
            ),
            43 =>
            array (
                'id' => 45,
                'name' => '分类管理',
                'parent_id' => 44,
                'auth_path' => '44_45_',
                'route_name' => '',
                'created_at' => 1603033116,
                'updated_at' => 1610520231,
            ),
            44 =>
            array (
                'id' => 46,
                'name' => '查看分类',
                'parent_id' => 45,
                'auth_path' => '44_45_46_',
                'route_name' => 'sensitive-word-category.index',
                'created_at' => 1603033150,
                'updated_at' => 1610520231,
            ),
            45 =>
            array (
                'id' => 47,
                'name' => '删除分类',
                'parent_id' => 45,
                'auth_path' => '44_45_47_',
                'route_name' => 'sensitive-word-category.destroy',
                'created_at' => 1603033164,
                'updated_at' => 1610520231,
            ),
            46 =>
            array (
                'id' => 48,
                'name' => '添加分类',
                'parent_id' => 45,
                'auth_path' => '44_45_48_',
                'route_name' => 'sensitive-word-category.store',
                'created_at' => 1603033184,
                'updated_at' => 1610520231,
            ),
            47 =>
            array (
                'id' => 49,
                'name' => '修改分类',
                'parent_id' => 45,
                'auth_path' => '44_45_49_',
                'route_name' => 'sensitive-word-category.update',
                'created_at' => 1603033211,
                'updated_at' => 1610520231,
            ),
            48 =>
            array (
                'id' => 50,
                'name' => '词汇管理',
                'parent_id' => 44,
                'auth_path' => '44_50_',
                'route_name' => '',
                'created_at' => 1603033227,
                'updated_at' => 1610520231,
            ),
            49 =>
            array (
                'id' => 51,
                'name' => '添加词汇',
                'parent_id' => 50,
                'auth_path' => '44_50_51_',
                'route_name' => 'sensitive-word.store',
                'created_at' => 1603033255,
                'updated_at' => 1610520231,
            ),
            50 =>
            array (
                'id' => 52,
                'name' => '查看词汇',
                'parent_id' => 50,
                'auth_path' => '44_50_52_',
                'route_name' => 'sensitive-word.index',
                'created_at' => 1603033295,
                'updated_at' => 1610520231,
            ),
            51 =>
            array (
                'id' => 53,
                'name' => '修改词汇',
                'parent_id' => 50,
                'auth_path' => '44_50_53_',
                'route_name' => 'sensitive-word.update',
                'created_at' => 1603033310,
                'updated_at' => 1610520231,
            ),
            52 =>
            array (
                'id' => 54,
                'name' => '删除词汇',
                'parent_id' => 50,
                'auth_path' => '44_50_54_',
                'route_name' => 'sensitive-word.destroy',
                'created_at' => 1603033332,
                'updated_at' => 1610520231,
            ),
            53 =>
            array (
                'id' => 56,
                'name' => '系统管理',
                'parent_id' => 0,
                'auth_path' => '56_',
                'route_name' => 'system.manage',
                'created_at' => 1603033390,
                'updated_at' => 1610520231,
            ),
            54 =>
            array (
                'id' => 59,
                'name' => '消息管理',
                'parent_id' => 0,
                'auth_path' => '59_',
                'route_name' => '',
                'created_at' => 1603033463,
                'updated_at' => 1610520231,
            ),
            55 =>
            array (
                'id' => 60,
                'name' => '举报信息管理',
                'parent_id' => 59,
                'auth_path' => '59_60_',
                'route_name' => '',
                'created_at' => 1603033487,
                'updated_at' => 1610520231,
            ),
            56 =>
            array (
                'id' => 61,
                'name' => '查看举报信息',
                'parent_id' => 60,
                'auth_path' => '59_60_61_',
                'route_name' => 'illegal-info.index',
                'created_at' => 1603033549,
                'updated_at' => 1610520231,
            ),
            57 =>
            array (
                'id' => 62,
                'name' => '删除被举报的内容',
                'parent_id' => 60,
                'auth_path' => '59_60_62_',
                'route_name' => 'illegal-info.approve',
                'created_at' => 1603033605,
                'updated_at' => 1610520231,
            ),
            58 =>
            array (
                'id' => 63,
                'name' => '忽略举报的内容',
                'parent_id' => 60,
                'auth_path' => '59_60_63_',
                'route_name' => 'illegal-info.ignore',
                'created_at' => 1603033628,
                'updated_at' => 1610520231,
            ),
            59 =>
            array (
                'id' => 64,
                'name' => '待审核评论管理',
                'parent_id' => 59,
                'auth_path' => '59_64_',
                'route_name' => '',
                'created_at' => 1603033646,
                'updated_at' => 1610520231,
            ),
            60 =>
            array (
                'id' => 65,
                'name' => '批准评论',
                'parent_id' => 64,
                'auth_path' => '59_64_65_',
                'route_name' => 'comment.approve',
                'created_at' => 1603033686,
                'updated_at' => 1610520231,
            ),
            61 =>
            array (
                'id' => 66,
                'name' => '查看待审核评论',
                'parent_id' => 64,
                'auth_path' => '59_64_66_',
                'route_name' => 'comment.index',
                'created_at' => 1603033721,
                'updated_at' => 1610520231,
            ),
            62 =>
            array (
                'id' => 67,
                'name' => '驳回评论',
                'parent_id' => 64,
                'auth_path' => '59_64_67_',
                'route_name' => 'comment.reject',
                'created_at' => 1603033783,
                'updated_at' => 1610520231,
            ),
            63 =>
            array (
                'id' => 68,
                'name' => '系统日志',
                'parent_id' => 56,
                'auth_path' => '56_68_',
                'route_name' => '',
                'created_at' => 1603374992,
                'updated_at' => 1610520231,
            ),
            64 =>
            array (
                'id' => 69,
                'name' => '系统设置',
                'parent_id' => 56,
                'auth_path' => '56_69_',
                'route_name' => '',
                'created_at' => 1603375052,
                'updated_at' => 1610520231,
            ),
            65 =>
            array (
                'id' => 70,
                'name' => '查看日志',
                'parent_id' => 68,
                'auth_path' => '56_68_70_',
                'route_name' => 'system.log',
                'created_at' => 1603377717,
                'updated_at' => 1610520231,
            ),
            66 =>
            array (
                'id' => 73,
                'name' => '通知',
                'parent_id' => 59,
                'auth_path' => '59_73_',
                'route_name' => 'notification.index',
                'created_at' => 1606314180,
                'updated_at' => 1610520231,
            ),
            67 =>
            array (
                'id' => 74,
                'name' => '查看系统设置',
                'parent_id' => 69,
                'auth_path' => '56_69_74_',
                'route_name' => 'system-setting.index',
                'created_at' => 1606316747,
                'updated_at' => 1610520231,
            ),
            68 =>
            array (
                'id' => 75,
                'name' => '更新系统设置',
                'parent_id' => 69,
                'auth_path' => '56_69_75_',
                'route_name' => 'system-setting.update',
                'created_at' => 1606316765,
                'updated_at' => 1610520231,
            ),
            69 =>
            array (
                'id' => 76,
                'name' => '账号设置',
                'parent_id' => 0,
                'auth_path' => '76_',
                'route_name' => '',
                'created_at' => 1606317062,
                'updated_at' => 1610520231,
            ),
            70 =>
            array (
                'id' => 77,
                'name' => '查看账号信息',
                'parent_id' => 76,
                'auth_path' => '76_77_',
                'route_name' => 'user.profile',
                'created_at' => 1606317317,
                'updated_at' => 1610520231,
            ),
            71 =>
            array (
                'id' => 78,
                'name' => '绑定社交账号',
                'parent_id' => 76,
                'auth_path' => '76_78_',
                'route_name' => 'user.bind',
                'created_at' => 1606317347,
                'updated_at' => 1610520231,
            ),
            72 =>
            array (
                'id' => 79,
                'name' => '取消绑定',
                'parent_id' => 76,
                'auth_path' => '76_79_',
                'route_name' => 'user.unbind',
                'created_at' => 1606317363,
                'updated_at' => 1610520231,
            ),
            73 =>
            array (
                'id' => 80,
                'name' => '重新绑定社交账号',
                'parent_id' => 76,
                'auth_path' => '76_80_',
                'route_name' => 'user.rebind',
                'created_at' => 1606317395,
                'updated_at' => 1610520231,
            ),
            74 =>
            array (
                'id' => 81,
                'name' => '修改基本信息',
                'parent_id' => 76,
                'auth_path' => '76_81_',
                'route_name' => 'user.update',
                'created_at' => 1606317432,
                'updated_at' => 1610520231,
            ),
            75 =>
            array (
                'id' => 82,
                'name' => '管理员界面获取用户信息',
                'parent_id' => 31,
                'auth_path' => '20_31_82_',
                'route_name' => 'manager.get.user',
                'created_at' => 1610519825,
                'updated_at' => 1610520231,
            ),
            76 =>
            array (
                'id' => 83,
                'name' => '访问后台',
                'parent_id' => 0,
                'auth_path' => '83_',
                'route_name' => 'admin.index',
                'created_at' => 1610520231,
                'updated_at' => 1610520231,
            ),
        ));


    }
}
