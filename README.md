复制wp_chenxing_message -> users_message

复制wp_chenxing_down_log -> users_down_log

执行各个迁移程序

添加wp_content 软链接

添加uisheauto 软链接 到storage/collection/uisheauto Windows mklink /D D:\xampp\htdocs\uishe\public\uisheauto D:\xampp\htdocs\uishe\storage\collection\uisheauto

git clone https://github.com/github-muzilong/laravel55-layuiadmin.git
复制.env.example为.env
配置.env里的数据库连接信息
composer update
php artisan migrate
php artisan db:seed
php artisan key:generate
登录后台：host/admin   帐号：root  密码：123456