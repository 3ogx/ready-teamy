<?php
/**
 * PHP實現守護進程
 *
 * @author 3ogx.com@gmail.com
 * @date 2013-05-05
 * @return void
 * @see:
 *  daemonize()
 *  sleep(1000)
 **/
function daemonize() {
    $pid = pcntl_fork();
    if (-1 == $pid) {
        die("Fork(1) failed\n");
    } elseif (0 < $pid) {
        /* 讓用戶啟動的進程退出 */
        exit(0);
    }

    /* 建立一個有別於終端機新session以脫離終端 */
    posix_setsid();

    $pid = pcntl_fork();
    if (-1 == $pid) {
        die("Fork(2) failed\n");
    } elseif (0 < $pid) {
        /* 父進程退出,剩下子進程成為最終的獨立進程 */
        exit(0);
    }
}