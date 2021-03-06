<?php 
function countingSort($arr) {

        $length = count($arr);
        if($length <= 1) return $arr;

        $size = count($arr);
        $max = $arr[0];

        //找出数组中最大的数
        for($i=1;$i<$size;$i++) {
            if($max < $arr[$i]) $max = $arr[$i];
        }

        //初始化用来计数的数组
        for ($i=0;$i<=$max;$i++) {
            $count_arr[$i] = 0;
        }

        //对计数数组中键值等于$arr[$i]的加1
        for($i=0;$i<$size;$i++) {
            $count_arr[$arr[$i]]++;
        }

        //相邻的两个值相加
        for($i=1;$i<=$max;$i++) {
            $count_arr[$i] = $count_arr[$i-1] + $count_arr[$i];
        }

        //键与值翻转
        for ($i=$size-1;$i>=0;$i--) {
            $over_turn[$count_arr[$arr[$i]]] = $arr[$i];
            $count_arr[$arr[$i]]--; // 前一个数找到位置后，那么和它值相同的数位置往前一步
        }

        //按照顺序排列
        $result = array();
        for ($i=1;$i<=$size;$i++) {
            array_push($result,$over_turn[$i]);
        }

        return $result;
    }
$arr = [2, 2, 3, 8, 7, 1, 2, 2, 2, 7, 3, 9, 8, 2, 1, 4, 2, 4, 6, 9, 2];
$countsort_start_time = microtime(true);

$countsort_sort = countingSort($arr);

$countsort_end_time = microtime(true);

$countsort_need_time = $countsort_end_time - $countsort_start_time;

print_r("计数排序耗时:" . $countsort_need_time . "<br />");
/*function countingSort($array) {
    $len = count($array);
        $B = [];
        $C = [];
        $min = $max = $array[0];
    // print_f('计数排序耗时');
    for ($i = 0; $i < $len; $i++) {
        $min = $min <= $array[$i] ? $min : $array[$i];
        $max = $max >= $array[$i] ? $max : $array[$i];
        $C[$array[$i]] = $C[$array[$i]] ? $C[$array[$i]] + 1 : 1;
    }
    for ($j = $min; $j < $max; $j++) {
        $C[$j + 1] = ($C[$j + 1] || 0) + ($C[$j] || 0);
    }
    for ($k = $len - 1; $k >= 0; $k--) {
        $B[$C[$array[$k]] - 1] = $array[$k];
        $C[$array[$k]]--;
    }
    // print_f('计数排序耗时');
    return $B;
}
$arr = [2, 2, 3, 8, 7, 1, 2, 2, 2, 7, 3, 9, 8, 2, 1, 4, 2, 4, 6, 9, 2];
$aa=countingSort($arr);
var_dump($aa); */


