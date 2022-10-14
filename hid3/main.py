import pymysql
import sys
import configparser
import time
import requests
from difflib import SequenceMatcher


config = configparser.ConfigParser()
config.read("dataBaseConfig.ini")
dbinfo = {'dbUrl': '127.0.0.1', 'dbUser': 'kongjiang', 'dbPass': 'kongjiang', 'dbName': 'j6kPAemtDAbNGkGF', 'dbPort': 3306}
def similarity(a, b):
  return SequenceMatcher(None, a, b).ratio()
def replace_str(source, key):
  tmp_ls = source.lstrip(key)
  return tmp_ls
def GetVideoToApp(db):
    i = 1
    cursor = db.cursor()
    cursor.execute("SELECT id,name FROM video_class")
    classdata = cursor.fetchall()
    while(1):
        url = "https://lbapi9.com/api.php/provide/vod/at/json/?ac=detail&pg=" + str(i)
        res = requests.get(url=url)
        jsonData = res.json()
        if jsonData['list']:
            print('检测到数据')
        else:
            print('采集结束')
            break
        for key in jsonData['list']:
            sql= "SELECT * FROM `video` WHERE vod_name like %s"
            param = (key['vod_name'])
            cursor.execute(sql, param)
            dataAllcol = cursor.fetchone()
            if dataAllcol:
                print("已采集过数据跳过")
            else:
                for datakey in classdata:
                    class_sim = similarity(key['type_name'], datakey[1])
                    name_sim = similarity(key['vod_name'], datakey[1])
                    if class_sim or name_sim >= 0.1:
                        videourl= key['vod_play_url'][key['vod_play_url'].rfind('https'):]
                        sql = "INSERT INTO video(vod_name,vod_pic,vod_play_url,vod_status,vod_class_id,vod_hot,create_time,update_time,vod_score_num,vod_duration) VALUES(%s,%s,%s,1,%s,0,%s,%s,%s,%s);"
                        param = (key['vod_name']
                                 , key['vod_pic']
                                 , videourl
                                 , datakey[0]
                                 ,int(time.time())
                                 ,int(time.time())
                                 , key['vod_score_num']
                                 , key['vod_duration']
                                 )
                        cursor.execute(sql, param)
                        db.commit()
                        print("正在采集 --- " + key['vod_name'] + " --- 到分类->" + datakey[1])
                        break
        i += 1
    cursor.close()
    db.close()
    print("----- 采集结束采集完成 ----- 时间 ："+time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())+" 一共采集了："+str(i*20)+"条视频数据")


userselect = input("选择新的数据库请输入 1 选择现有的数据库请输入 2 \n")
try:
    if userselect == '1':
        config.remove_section('info')
        dbinfo['dbUrl'] = input("请输入数据库的地址：\n")
        dbinfo['dbUser'] = input("请输入数据库的用户名：\n")
        dbinfo['dbPass'] = input("请输入数据库的密码：\n")
        dbinfo['dbName'] = input("请输入数据库的名称：\n")
        dbinfo['dbPort'] = input("请输入数据库的端口：\n")
        try:
            config.add_section("info")
            config.set("info", "dbUrl", dbinfo['dbUrl'])
            config.set("info", "dbUser", dbinfo['dbUser'])
            config.set("info", "dbPass", dbinfo['dbPass'])
            config.set("info", "dbName", dbinfo['dbName'])
            config.set("info", "dbPort", dbinfo['dbPort'])
        except configparser.DuplicateSectionError:
            print("错误：检测到同名项")
        config.write(open("dataBaseConfig.ini", "w+"))
    elif userselect == '2':
        dbinfo['dbUrl'] = config.get("info", "dbUrl")
        dbinfo['dbUser'] = config.get("info", "dbUser")
        dbinfo['dbPass'] = config.get("info", "dbPass")
        dbinfo['dbName'] = config.get("info", "dbName")
        dbinfo['dbPort'] = config.get("info", "dbPort")


    db = pymysql.connect(host=dbinfo['dbUrl'], port=int(dbinfo['dbPort']), user=dbinfo['dbUser'],password=dbinfo['dbPass'], database=dbinfo['dbName'], charset="utf8")

    GetVideoToApp(db)

except:
    print("Unexpected error:", sys.exc_info()[0])
    raise
