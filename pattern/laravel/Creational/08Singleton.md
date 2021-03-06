# PHP 设计模式系列 —— 单例模式（Singleton）

 Posted on [2015年12月15日][0] by [学院君][1]

### **1、模式定义**

简单说来，[单例模式][2]的作用就是保证在整个应用程序的生命周期中，任何一个时刻，单例类的实例都只存在一个，同时这个类还必须提供一个访问该类的全局访问点。

常见使用实例：数据库连接器；日志记录器（如果有多种用途使用多例模式）；锁定文件。

### **2、UML类图**

![单例模式类图][3]

### **3、示例代码**

#### **[Singleton][4].php**

```php
<?php

namespace DesignPatterns\Creational\Singleton;

/**
 * Singleton类
 */
class Singleton
{
    /**
     * @var Singleton reference to singleton instance
     */
    private static $instance;
    
    /**
     * 通过延迟加载（用到时才加载）获取实例
     *
     * @return self
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * 构造函数私有，不允许在外部实例化
     *
     */
    private function __construct()
    {
    }

    /**
     * 防止对象实例被克隆
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * 防止被反序列化
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}
```

### **4、测试代码**

**Tests/SingletonTest.php**

```php
<?php

namespace DesignPatterns\Creational\Singleton\Tests;

use DesignPatterns\Creational\Singleton\Singleton;

/**
 * SingletonTest用于测试单例模式
 */
class SingletonTest extends \PHPUnit\Framework\TestCase
{

    public function testUniqueness()
    {
        $firstCall = Singleton::getInstance();
        $this->assertInstanceOf('DesignPatterns\Creational\Singleton\Singleton', $firstCall);
        $secondCall = Singleton::getInstance();
        $this->assertSame($firstCall, $secondCall);
    }

    public function testNoConstructor()
    {
        $obj = Singleton::getInstance();

        $refl = new \ReflectionObject($obj);
        $meth = $refl->getMethod('__construct');
        $this->assertTrue($meth->isPrivate());
    }
}
```

[0]: http://laravelacademy.org/post/2599.html
[1]: http://laravelacademy.org/post/author/nonfu
[2]: http://laravelacademy.org/tags/%e5%8d%95%e4%be%8b%e6%a8%a1%e5%bc%8f
[3]: ../img/7a7b8a19-a341-4aa2-9d59-81bc5d2a312a.png
[4]: http://laravelacademy.org/tags/singleton
[5]: http://laravelacademy.org/tags/php