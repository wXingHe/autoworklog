<?php 
    //模拟登陆
	$post = [
		"account" => "账号",
		"password" => "密码"
	];

	$url = "登录入口";
	$cookie = "./cookie.ck";
	$curl = curl_init();//初始化curl模块   
    curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址   
    curl_setopt($curl, CURLOPT_HEADER, 1);//是否显示头信息   
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息   
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); //设置Cookie信息保存在指定的文件中   
    curl_setopt($curl, CURLOPT_POST, 1);//post方式提交   
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));//提交账号密码   
    curl_exec($curl);//执行cURL   
    curl_close($curl);//关闭cURL资源，并且释放系统资源   

    date_default_timezone_set("PRC"); //设置时区,以防环境时间不正确
    $filename = mktime(0,0,0,date('m'),date('d'),date('Y')); //今日时间戳
    
    //获取今日提交内容
    $json = file_get_contents("../getcommit/commitLog/".$filename.".cm");
    $commit = json_decode($json,1);
    
    //提交日志
    if(count($commit) > 0 ){
      foreach($commit as $item){
          $url = "提交日志入口";
          $ch = curl_init();   
          curl_setopt($ch, CURLOPT_URL, $url);   
          curl_setopt($ch, CURLOPT_HEADER, 0);   
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
          curl_setopt($ch, CURLOPT_POSTFIELDS, $item);//要提交的信息  
          curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //读取cookie   
          $rejson = curl_exec($ch); //执行cURL提交   
          curl_close($ch);   

          //处理结果
          $re = json_decode($rejson,1)['code'];
          if($re == 0){
            echo $item['content']."提交成功";
          }else{
            echo $item['content']."提交失败";
          }
      }
    }else{
      echo "今日无commit内容";
    }

 
 ?>