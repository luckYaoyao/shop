<?php

namespace PHPSTORM_META {

    use think\Container;
    use function \app;
    // 容器注入
    override(
        \app(),
        map([
            'json' => \crmeb\utils\Json::class // json类
        ])
    );
    override(
        \think\Container::make(),// 容器实例化
        map([
            '' => '@'
        ])
    );

}
