# MySQL锁系列（六）之 MVCC

 时间 2017-06-16 19:12:28  Focus on MySQL

原文[http://keithlan.github.io/2017/06/16/innodb_locks_MVCC/][1]


## agenda

我们能学到什么

* 什么是MVCC
* MVCC能解决什么问题
* MVCC的实现原理

## 一、什么是MVCC

* 名词解释

    英文名：Multi Version Concurrency Control
    中文名：多版本一致性控制
    

* 应用场景

1. 大家有没有这样的疑问，线上的表一直被更新，可是为什么还可以去select呢?
1. 我的更新事务还没有提交，为什么另外一个事务可以读到数据呢？
1. 我的更新事务已经提交，另一个事务又是怎么选择数据返回给用户呢？

## 二、MVCC能解决什么问题

* 解决的问题
    1. snapshot查询不会加锁，读和读，读和写之间互不影响，提高数据库的并发能力  
    2. 隔离级别的实现
    

## 三、MVCC的实现原理

### 3.1 row的记录格式

记住这个格式，很重要

![][3]

### 3.2 row 和 undo

![][4]

### 3.3 readview

![][5]

### 3.4 可见性判断

* 实现原理

```
    readview = 活跃事务列表
    readview（RR）： 事务开始时产生readview
    readview（RC）： 每条语句都会产生readview
    
    如何判断可见性：
    
    假设：活跃事务为（3，4，5，6）=readview，当前事务id号为10，做了修改这条记录 ， 那么这条记录上的db_trx_id=10
    
    流程如下：
    
    当前事务(trxid=10)拿着刚刚产生的readview =（3[active_trx_min]，4，5，6【active_trx_max】）去查看记录，
    
    
    1.如果row上的db_trx_id in (活跃事务列表),那么说明此记录还未提交，这条记录对于此事务不可见.需要调用上一个undo，用同样的判断标准过滤，循环
    2.如果row上的db_trx_id < 活跃事务列表最小值，那么说明已经提交，这条记录对于此事务可见
    3.如果row上的db_trx_id > 活跃事务列表最大值, 那么说明该记录在当前事务之后提交，这条记录对于此事务不可见.需要调用上一个undo，用同样的判断标准过滤，循环
    
    
    这里有个问题： 当前事务id更新后，会锁住该记录并更新db_trx_id=10，那么该记录上的trx_id肯定是<=当前事务id（10）的，那既然这样，怎么会产生db_trx_id > 活跃事务列表最大值呢？
    
    原因：因为当前事务不仅仅是读取这条被锁住的记录，可能还需要读取其他记录（这些记录当然可能被其他更靠后的事务id更新了），那么这时候其他记录上的db_trx_id>=10就很正常不过了。
    
    创建readview的位置，不是begin的那个位置，而是begin后面的SQL语句的位置。（换句话说：就是begin的时候不会分配事务id，只有执行了sql之后才会分配事务id）
    如果你想在开启transaction的时候就产生readview，分配事务id，那么可以这样操作：start transaction with consistent snapshot
    
    percona 中可以有这样的信息，官方没有: Trx read view will not see trx with id >= 413  , sees < 411
```

* 案例剖析

![][6]


[1]: http://keithlan.github.io/2017/06/16/innodb_locks_MVCC/

[3]: ./img/VnUniir.jpg
[4]: ./img/QBnQRj2.jpg
[5]: ./img/BrAzUzu.jpg
[6]: ./img/Yb2UN3y.jpg