const fs = require('fs');
// const http = require('http');
const branch = "dev";
const tag = 'commit测试'; //标签
const todayTimeStamp = new Date(new Date().toLocaleDateString()).getTime(); //今天的时间戳

let lines = fs.readLines("../../.git/logs/refs/heads/"+branch); //读取git日志文件
let content = lines[0];
let commit = [];
let rows = content.split('\n');
let reg1 = /> (\d+) \+0800/i; //匹配时间戳正则
let reg2 = /commit(\s\(merge\))?: (.*)/i; //匹配commit内容,包括合并内容正则

//遍历每行日志,获取今日提交内容,放入commit
rows.forEach((row, index, array)=>{
    let match1 = reg1.exec(row);
    let match2 = reg2.exec(row);
    if(match2){ //过滤commit以外的操作
    	if(match1[1] < todayTimeStamp/1000){ //过滤非今日commit
    		return true ; //foreach中的continue使用return 代替
    	}
    	else{
    		let datetime = timestampToTime(match1[1]).split(' ');
    		let date = datetime[0]; //日期
    		let time = datetime[1]; //时间

    		//拼装今日用于提交的日志
			let line = {
				date : date,
	    		time : time,
	    		tag : tag,
	    		content:match2[2]
	    	}
	    	commit.push(line);
    	}
    }
});

if(commit.length>0){
	var commitContent = JSON.stringify(commit);
	fs.writeTextFile("./commitLog/"+todayTimeStamp/1000+".cm",commitContent);
}else{
	console.log("今日无commit内容");
}

function timestampToTime(timestamp) {
        var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
        Y = date.getFullYear() + '-';
        M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
        D = date.getDate() + ' ';
        h = date.getHours() + ':';
        m = date.getMinutes() + ':';
        s = date.getSeconds();
        return Y+M+D+h+m+s;
}